<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use App\Models\User;
use App\Notifications\GymNoticeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class NoticeController extends Controller implements HasMiddleware
{
    /**
     * Configurar middleware para el controlador
     */
    public static function middleware(): array
    {
        return [
            'auth',
            // Aplicar rol admin solo a métodos de gestión, excluyendo los de estudiantes
            new Middleware('role:administrador', except: ['studentIndex', 'markAsRead', 'notificationsJson', 'markAllAsRead']),
        ];
    }

    public function index()
    {
        $notices = Notice::with('author')->latest()->paginate(10);
        // Esto ahora funcionará porque creamos la carpeta notices
        return view('admin.notices.index', compact('notices'));
    }

    public function create()
    {
        return view('admin.notices.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:info,warning,success,danger',
        ]);

        $notice = Notice::create(array_merge($validated, ['admin_id' => auth()->id(), 'is_active' => true]));

        // Enviar notificación a todos los usuarios si se solicita
        if ($request->boolean('notify_now')) {
            $users = User::all();
            Notification::send($users, new GymNoticeNotification($notice));
            return redirect()->route('admin.notices.index')->with('success', 'Aviso publicado y notificado a todos los usuarios.');
        }

        return redirect()->route('admin.notices.index')->with('success', 'Aviso publicado correctamente. (No notificado)');
    }

    public function edit(Notice $notice)
    {
        return view('admin.notices.edit', compact('notice'));
    }

    public function show(Notice $notice)
    {
        return view('admin.notices.show', compact('notice'));
    }

    public function update(Request $request, Notice $notice)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:info,warning,success,danger',
            'is_active' => 'required|boolean',
        ]);

        $notice->update($validated);
        return redirect()->route('admin.notices.index')->with('success', 'Aviso actualizado.');
    }

    public function destroy(Notice $notice)
    {
        $notice->delete();
        return back()->with('success', 'Aviso eliminado.');
    }

    /**
     * Método para que el DashboardController obtenga los avisos activos.
     */
    public static function getActiveNotices()
    {
        return Notice::where('is_active', true)->latest()->get();
    }

    /**
     * Vista para que estudiantes consulten avisos públicos y sus notificaciones en base de datos.
     */
    public function studentIndex()
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Avisos activos creados por admin (modelo Notice)
        $notices = Notice::where('is_active', true)->latest()->get();

        // Notificaciones en la tabla notifications (canal database)
        $unreadNotifications = $user->unreadNotifications;

        return view('student.notices.index', compact('notices', 'unreadNotifications'));
    }

    /**
     * Marca una notificación de la tabla `notifications` como leída para el usuario autenticado.
     */
    public function markAsRead($notificationId)
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        $notification = $user->notifications()->where('id', $notificationId)->first();

        if (! $notification) {
            return back()->with('error', 'Notificación no encontrada.');
        }

        $notification->markAsRead();
        // Si es petición AJAX, devolver JSON
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Notificación marcada como leída.');
    }

    /**
     * Devuelve JSON con notificaciones (útil para AJAX) del usuario autenticado.
     */
    public function notificationsJson()
    {
        if (! auth()->check()) {
            return response()->json(['error' => 'No autenticado'], 401);
        }

        $user = auth()->user();
        $unread = $user->unreadNotifications()->orderBy('created_at', 'desc')->get();
        return response()->json(['unread' => $unread, 'count' => $unread->count()]);
    }

    /**
     * Marca todas las notificaciones del usuario como leídas.
     */
    public function markAllAsRead()
    {
        if (! auth()->check()) {
            return response()->json(['error' => 'No autenticado'], 401);
        }

        $user = auth()->user();
        $user->unreadNotifications->markAsRead();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Todas las notificaciones marcadas como leídas.');
    }
}
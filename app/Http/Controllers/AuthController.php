<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // Mostrar formulario de registro
    public function showRegisterForm()
    {
        return view('auth.register');
    }
    
    // Procesar registro
    public function register(Request $request)
    {
        try {
            // Validar que el email sea del dominio institucional
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users|ends_with:@ucundinamarca.edu.co',
                'password' => 'required|string|min:8|confirmed',
            ], [
                'email.ends_with' => 'El correo debe ser del dominio @ucundinamarca.edu.co',
                'email.unique' => 'Este correo ya está registrado',
                'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            ]);
            
            // Crear usuario con rol por defecto
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'estudiante',
            ]);
            
            // Autenticar al usuario
            Auth::login($user);
            
            return redirect('/dashboard')->with('success', '¡Bienvenido a GymUdec! Tu cuenta ha sido creada exitosamente.');
        } catch (\Exception $e) {
            return back()->withInput($request->only('name', 'email'))->withErrors([
                'error' => 'Error al registrar: ' . $e->getMessage()
            ]);
        }
    }
    
    // Mostrar formulario de login
    public function showLoginForm()
    {
        return view('auth.login');
    }
    
    // Procesar login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        
        // Intentar autenticar
        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ])) {
            return redirect('/dashboard')->with('success', '¡Sesión iniciada correctamente!');
        }
        
        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ]);
    }

    // Mostrar formulario de "Olvidé mi contraseña"
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    // Enviar enlace de recuperación
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', '¡Te hemos enviado por correo el enlace de recuperación!')
            : back()->withErrors(['email' => 'No pudimos enviar el correo de recuperación.']);
    }

    // Mostrar formulario para resetear contraseña
    public function showResetForm(Request $request, $token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    // Procesar el cambio de contraseña
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', '¡Tu contraseña ha sido restablecida!')
            : back()->withErrors(['email' => [__($status)]]);
    }
    
    // Logout
    public function logout()
    {
        Auth::logout();
        return redirect('/')->with('success', 'Sesión cerrada correctamente');
    }
}

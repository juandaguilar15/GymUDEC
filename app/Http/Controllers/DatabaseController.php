<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DatabaseController extends Controller
{
    /**
     * Verifica que el usuario sea administrador.
     */
    private function authorizeAdmin()
    {
        if (! auth()->check() || auth()->user()->role !== 'administrador') {
            abort(403, 'Acceso denegado. Solo administradores pueden gestionar la base de datos.');
        }
    }

    /**
     * Exporta la base de datos y la descarga como un archivo .sql
     */
    public function export()
    {
        $this->authorizeAdmin();

        $filename = "backup-gymudec-" . now()->format('Y-m-d_H-i-s') . ".sql";
        
        $dbConfig = config('database.connections.mysql');
        $host = $dbConfig['host'];
        $username = $dbConfig['username'];
        $password = $dbConfig['password'];
        $database = $dbConfig['database'];

        // Comando para exportar la base de datos
        // Usamos escapeshellarg por seguridad para evitar inyección de comandos
        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s',
            escapeshellarg($username),
            escapeshellarg($password),
            escapeshellarg($host),
            escapeshellarg($database)
        );

        return new StreamedResponse(function () use ($command) {
            $handle = popen($command, 'r');
            while (!feof($handle)) {
                echo fread($handle, 1024 * 8);
                flush();
            }
            pclose($handle);
        }, 200, [
            'Content-Type' => 'application/sql',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Restaura la base de datos desde un archivo .sql subido
     */
    public function import(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'backup_file' => 'required|file|mimetypes:text/plain,application/sql,text/x-sql',
        ], [
            'backup_file.required' => 'Por favor, selecciona un archivo de respaldo.',
            'backup_file.mimetypes' => 'El archivo debe ser un script SQL válido.',
        ]);

        $file = $request->file('backup_file');
        $path = $file->getRealPath();

        $dbConfig = config('database.connections.mysql');
        $host = $dbConfig['host'];
        $username = $dbConfig['username'];
        $password = $dbConfig['password'];
        $database = $dbConfig['database'];

        // Comando para importar la base de datos
        // < es el operador de redirección para inyectar el archivo al comando mysql
        $command = sprintf(
            'mysql --user=%s --password=%s --host=%s %s < %s',
            escapeshellarg($username),
            escapeshellarg($password),
            escapeshellarg($host),
            escapeshellarg($database),
            escapeshellarg($path)
        );

        try {
            $output = [];
            $returnVar = null;
            
            // Ejecutamos el comando
            exec($command, $output, $returnVar);

            if ($returnVar !== 0) {
                return back()->with('error', 'Hubo un error al restaurar la base de datos. Verifica el archivo.');
            }

            // Importante: Si se restauró la tabla de usuarios, el admin podría necesitar volver a loguearse
            // si los IDs o sesiones cambiaron, pero usualmente redirigimos al dashboard.
            return redirect()->route('admin.index')->with('success', 'Base de datos restaurada exitosamente.');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error crítico: ' . $e->getMessage());
        }
    }
}
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class EstudioMiddleware
{
    public function handle($request, Closure $next)
    {
        if ($request->is('login') && $request->isMethod('post')) {
            [$usuario, $estudio] = explode('@', $request->input('email'));

            // Buscar estudio y configurar conexión (como ya hicimos antes)
            $estudioDB = DB::table('estudios')->where('slug', $estudio)->first();

            if (!$estudioDB) {
                return back()->withErrors(['email' => 'Estudio no válido']);
            }

            config([
                'database.connections.tenant' => [
                    'driver' => 'mysql',
                    'host' => env('DB_HOST'),
                    'database' => $estudioDB->base_datos,
                    'username' => env('DB_USERNAME'),
                    'password' => env('DB_PASSWORD'),
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                ],
            ]);

            DB::purge('tenant');
            DB::reconnect('tenant');

            session(['estudio_slug' => $estudio, 'usuario_simple' => $usuario]);
        }

        return $next($request);
    }
}

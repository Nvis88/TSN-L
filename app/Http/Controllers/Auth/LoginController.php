<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!str_contains($request->email, '@')) {
            return back()->withErrors(['email' => 'Formato de usuario inválido']);
        }

        [$nombreUsuario, $slugEstudio] = explode('@', $request->email);

        $usuario = DB::connection('tenant')->table('usuarios')
            ->where('nombre', $nombreUsuario)
            ->first();

        if ($usuario && Hash::check($request->password, $usuario->password)) {
            session(['usuario' => [
                'nombre' => $usuario->nombre,
                'rol' => $usuario->rol,
                'estudio' => $slugEstudio,
            ]]);
            return redirect('/dashboard');
        }

        return back()->withErrors(['email' => 'Credenciales inválidas']);
    }

    public function logout()
    {
        session()->flush();
        return redirect('/login');
    }
}

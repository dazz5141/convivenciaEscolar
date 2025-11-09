<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Auditoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {

            $usuario = Auth::user();

            if (!$usuario->activo) {
                Auth::logout();
                return back()->withErrors(['email' => 'Este usuario está deshabilitado.']);
            }

            // Guardar establecimiento para el middleware
            session(['establecimiento_id' => $usuario->establecimiento_id]);

            // REGISTRO DE LOGIN
            Auditoria::create([
                'usuario_id'        => $usuario->id,
                'establecimiento_id'=> $usuario->establecimiento_id,
                'accion'            => 'login',
                'modulo'            => 'Autenticación',
                'detalle'           => 'Usuario inició sesión correctamente',
            ]);

            // Redirección según rol
            return redirect()->intended($this->redirectByRole());
        }

        return back()->withErrors(['email' => 'Credenciales incorrectas.']);
    }

    public function logout(Request $request)
    {
        $usuario = Auth::user();

        // REGISTRO DE LOGOUT
        Auditoria::create([
            'usuario_id'        => $usuario->id,
            'establecimiento_id'=> $usuario->establecimiento_id,
            'accion'            => 'logout',
            'modulo'            => 'Autenticación',
            'detalle'           => 'Usuario cerró sesión',
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // Redirecciones por rol
    private function redirectByRole()
    {
        $rol = Auth::user()->rol_id;

        return match ($rol) {
            1 => route('dashboard.admin'),               // Administrador General
            2 => route('dashboard.establecimiento'),     // Admin Establecimiento
            3 => route('dashboard.inspector.general'),   // Inspector General
            4 => route('dashboard.inspector'),           // Inspector
            5 => route('dashboard.docente'),             // Profesor
            6 => route('dashboard.psicologo'),           // Psicólogo
            7 => route('dashboard.asistente'),           // Asistente
            8 => route('dashboard.convivencia'),         // Encargado Convivencia
            default => route('dashboard'),
        };
    }
}

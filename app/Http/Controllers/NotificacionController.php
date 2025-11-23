<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    // Mostrar lista completa del usuario
    public function index()
    {
        $notificaciones = Notificacion::where('usuario_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('modulos.notificaciones.index', compact('notificaciones'));
    }

    // Marcar como leída desde AJAX o simple GET
    public function marcarLeida(Notificacion $notificacion)
    {
        if ($notificacion->usuario_id == auth()->id()) {
            $notificacion->update(['leida' => 1]);
        }

        return back();
    }

    // Marcar todas como leídas
    public function marcarTodas()
    {
        Notificacion::where('usuario_id', auth()->id())
            ->update(['leida' => 1]);

        return back();
    }
}

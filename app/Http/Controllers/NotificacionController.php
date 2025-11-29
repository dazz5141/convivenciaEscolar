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

        // Auditoría
        logAuditoria(
            accion: 'update',
            modulo: 'notificaciones',
            detalle: 'Marcó como leída la notificación ID ' . $notificacion->id,
            establecimiento_id: session('establecimiento_id')
        );

        return back();
    }

    // Marcar todas como leídas
    public function marcarTodas()
    {
        Notificacion::where('usuario_id', auth()->id())
            ->update(['leida' => 1]);

            // Auditoría global
            logAuditoria(
                accion: 'update',
                modulo: 'notificaciones',
                detalle: 'Marcó todas las notificaciones como leídas.',
                establecimiento_id: session('establecimiento_id')
            );
            
        return back();
    }
}

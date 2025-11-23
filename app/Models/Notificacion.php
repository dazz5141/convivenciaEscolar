<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    protected $table = 'notificaciones';

    protected $fillable = [
        'tipo',
        'mensaje',
        'usuario_id',
        'origen_id',
        'origen_model',
        'establecimiento_id',
        'leida'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function origen()
    {
        return $this->morphTo(null, 'origen_model', 'origen_id');
    }

    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class);
    }
}

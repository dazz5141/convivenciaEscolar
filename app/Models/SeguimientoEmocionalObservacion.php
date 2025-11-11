<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SeguimientoEmocionalObservacion extends Model
{
    use HasFactory;

    protected $table = 'seguimiento_emocional_observaciones';

    protected $fillable = [
        'seguimiento_id',
        'observacion',
        'agregado_por',
        'fecha_observacion',
    ];

    /* =============================
        RELACIONES
    ==============================*/

    // Un historial pertenece a un seguimiento emocional
    public function seguimiento()
    {
        return $this->belongsTo(SeguimientoEmocional::class, 'seguimiento_id');
    }

    // Funcionario que agregó la observación
    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class, 'agregado_por');
    }

    /* =============================
        SCOPES ÚTILES
    ==============================*/

    // Ordenar siempre por fecha (timeline)
    public function scopeOrdenado($query)
    {
        return $query->orderBy('fecha_observacion', 'asc');
    }
}

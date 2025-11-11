<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BitacoraIncidenteObservacion extends Model
{
    use HasFactory;

    protected $table = 'bitacora_incidente_observaciones';

    protected $fillable = [
        'incidente_id',
        'observacion',
        'agregado_por',
        'fecha_observacion',
    ];

    /* =============================
        RELACIONES
    ==============================*/

    // Relación con la bitácora
    public function incidente()
    {
        return $this->belongsTo(BitacoraIncidente::class, 'incidente_id');
    }

    // Funcionario que agregó la observación
    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class, 'agregado_por');
    }

    // Timeline ordenado por fecha
    public function scopeOrdenado($query)
    {
        return $query->orderBy('fecha_observacion', 'asc');
    }
}

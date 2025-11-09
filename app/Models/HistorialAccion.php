<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HistorialAccion extends Model
{
    use HasFactory;

    protected $table = 'historial_acciones';

    protected $fillable = [
        'entidad',
        'entidad_id',
        'funcionario_id',
        'accion',
        'fecha',
        'establecimiento_id'
    ];

    // Relación con entidad polimórfica
    public function entidadRelacionada()
    {
        return $this->morphTo(__FUNCTION__, 'entidad', 'entidad_id');
    }

    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class);
    }

    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class);
    }

    public function scopeDelColegio($query, $id)
    {
        return $query->where('establecimiento_id', $id);
    }
}

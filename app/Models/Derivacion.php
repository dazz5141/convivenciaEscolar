<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Derivacion extends Model
{
    use HasFactory;

    protected $table = 'derivaciones';

    protected $fillable = [
        'alumno_id',
        'entidad_type',
        'entidad_id',
        'tipo',
        'destino',
        'motivo',
        'estado',
        'fecha',
        'registrado_por',
        'establecimiento_id'
    ];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }

    public function entidad()
    {
        return $this->morphTo();
    }

    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class, 'registrado_por');
    }

    public function scopeDelColegio($query, $id)
    {
        return $query->where('establecimiento_id', $id);
    }
}

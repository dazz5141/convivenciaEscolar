<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SeguimientoEmocional extends Model
{
    use HasFactory;

    protected $table = 'seguimiento_emocional';

    protected $fillable = [
        'alumno_id',
        'fecha',
        'nivel_emocional_id',
        'comentario',
        'evaluado_por',
        'establecimiento_id'
    ];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }

    public function nivel()
    {
        return $this->belongsTo(NivelEmocional::class, 'nivel_emocional_id');
    }

    public function evaluador()
    {
        return $this->belongsTo(Funcionario::class, 'evaluado_por');
    }

    public function scopeDelColegio($query, $id)
    {
        return $query->where('establecimiento_id', $id);
    }
}

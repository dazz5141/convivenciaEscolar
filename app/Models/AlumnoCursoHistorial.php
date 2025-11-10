<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AlumnoCursoHistorial extends Model
{
    use HasFactory;

    protected $table = 'alumno_curso_historial';

    protected $fillable = [
        'alumno_id',
        'curso_id',
        'establecimiento_id',
        'fecha_cambio',
        'motivo',
    ];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class);
    }
}

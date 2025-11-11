<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BitacoraIncidenteAlumno extends Model
{
    use HasFactory;

    protected $table = 'bitacora_incidente_alumno';

    protected $fillable = [
        'incidente_id',
        'alumno_id',
        'rol',
        'curso_id',
        'observaciones',
        'establecimiento_id'
    ];

    public function incidente()
    {
        return $this->belongsTo(BitacoraIncidente::class, 'incidente_id');
    }

    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'alumno_id');
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }

    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class);
    }
}

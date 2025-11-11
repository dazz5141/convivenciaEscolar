<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Curso extends Model
{
    use HasFactory;

    protected $table = 'cursos';

    protected $fillable = [
        'anio',
        'nivel',
        'letra',
        'establecimiento_id',
        'activo'
    ];

    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class);
    }

    public function alumnos()
    {
        return $this->hasMany(Alumno::class);
    }

    public function incidentesHistoricos()
    {
        return $this->hasMany(BitacoraIncidenteAlumno::class, 'curso_id');
    }

    public function scopeDelColegio($query, $id)
    {
        return $query->where('establecimiento_id', $id);
    }

    public function getNombreAttribute()
    {
        return "{$this->nivel} {$this->letra} - {$this->anio}";
    }
}

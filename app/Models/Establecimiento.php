<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Establecimiento extends Model
{
    use HasFactory;

    protected $table = 'establecimientos';

    protected $fillable = [
        'RBD',
        'nombre',
        'direccion',
        'dependencia_id',
        'region_id',
        'provincia_id',
        'comuna_id',
        'activo',
    ];

    // Relaciones con catálogos
    public function dependencia()
    {
        return $this->belongsTo(Dependencia::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function provincia()
    {
        return $this->belongsTo(Provincia::class);
    }

    public function comuna()
    {
        return $this->belongsTo(Comuna::class);
    }

    // Relaciones jerárquicas del sistema
    public function cursos()
    {
        return $this->hasMany(Curso::class);
    }

    public function funcionarios()
    {
        return $this->hasMany(Funcionario::class);
    }

    public function alumnos()
    {
        return $this->hasMany(Alumno::class);
    }

    public function modulos()
    {
        return $this->hasMany(ModuloHabilitado::class);
    }

    // PIE
    public function estudiantesPie()
    {
        return $this->hasMany(EstudiantePie::class);
    }

    // Scope para filtrar por establecimiento
    public function scopeDelColegio($query, $establecimiento_id)
    {
        return $query->where('id', $establecimiento_id);
    }
}

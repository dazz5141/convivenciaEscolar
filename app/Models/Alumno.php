<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Alumno extends Model
{
    use HasFactory;

    protected $table = 'alumnos';

    protected $fillable = [
        'run',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'curso_id',
        'sexo_id',
        'telefono',
        'direccion',
        'email',
        'fecha_nacimiento',
        'fecha_ingreso',
        'fecha_egreso',
        'observaciones',
        'region_id',
        'provincia_id',
        'comuna_id',
        'activo'
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'fecha_ingreso' => 'date',
        'fecha_egreso' => 'date',
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function sexo()
    {
        return $this->belongsTo(Sexo::class);
    }

    public function apoderados()
    {
        return $this->belongsToMany(Apoderado::class, 'alumnos_apoderados')
                     ->withPivot('tipo');
    }

    public function seguimientos()
    {
        return $this->hasMany(SeguimientoEmocional::class);
    }

    public function bitacoras()
    {
        return $this->hasMany(BitacoraIncidente::class);
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', 1);
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'provincia_id');
    }

    public function comuna()
    {
        return $this->belongsTo(Comuna::class, 'comuna_id');
    }

    public function historialCursos()
    {
        return $this->hasMany(AlumnoCursoHistorial::class);
    }
}

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

    // -------------------------------
    // RELACIONES
    // -------------------------------

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

    // Relación a la bitácora antigua (solo 1 alumno)
    //    Se mantiene por compatibilidad pero ya no se usará
    public function bitacoras()
    {
        return $this->hasMany(BitacoraIncidente::class);
    }

    // NUEVA: Relación muchos-a-muchos REAL
    public function incidentes()
    {
        return $this->belongsToMany(BitacoraIncidente::class, 'bitacora_incidente_alumno', 'alumno_id', 'incidente_id')
                    ->withPivot(['rol', 'curso_id', 'comentario'])
                    ->withTimestamps();
    }

    // SCOPES
    public function scopeActivos($query)
    {
        return $query->where('activo', 1);
    }

    // Datos geográficos
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

    // Historial académico
    public function historialCursos()
    {
        return $this->hasMany(AlumnoCursoHistorial::class);
    }

    // FILTROS POR ROL DENTRO DEL INCIDENTE

    public function victimaEn()
    {
        return $this->incidentes()->wherePivot('rol', 'victima');
    }

    public function agresorEn()
    {
        return $this->incidentes()->wherePivot('rol', 'agresor');
    }

    public function testigoEn()
    {
        return $this->incidentes()->wherePivot('rol', 'testigo');
    }
}

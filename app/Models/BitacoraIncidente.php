<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BitacoraIncidente extends Model
{
    use HasFactory;

    protected $table = 'bitacora_incidentes';

    protected $fillable = [
        'fecha',
        'tipo_incidente',
        'descripcion',
        'accion_realizada',
        'reportado_por',
        'estado_id',
        'seguimiento_id',
        'establecimiento_id',
        'curso_id'
    ];

    // ---------------------------------------------------
    // RELACIONES PRINCIPALES
    // ---------------------------------------------------

    // Funcionario que reportó el incidente
    public function reportadoPor()
    {
        return $this->belongsTo(Funcionario::class, 'reportado_por');
    }

    // Estado del incidente
    public function estado()
    {
        return $this->belongsTo(EstadoIncidente::class, 'estado_id');
    }

    // Seguimiento emocional generado automáticamente
    public function seguimiento()
    {
        return $this->belongsTo(SeguimientoEmocional::class, 'seguimiento_id');
    }

    // Involucrados (acceso directo a la tabla pivot)
    public function involucrados()
    {
        return $this->hasMany(BitacoraIncidenteAlumno::class, 'incidente_id');
    }

    // Relación muchos-a-muchos REAL con alumnos
    public function alumnos()
    {
        return $this->belongsToMany(Alumno::class, 'bitacora_incidente_alumno', 'incidente_id', 'alumno_id')
                    ->withPivot(['rol', 'curso_id', 'observaciones'])
                    ->withTimestamps();
    }

    // Roles filtrados (usando pivot)
    public function victimas()
    {
        return $this->hasMany(BitacoraIncidenteAlumno::class, 'incidente_id')
                    ->where('rol', 'victima')
                    ->with('alumno.curso');
    }

    public function agresores()
    {
        return $this->hasMany(BitacoraIncidenteAlumno::class, 'incidente_id')
                    ->where('rol', 'agresor')
                    ->with('alumno.curso');
    }

    public function testigos()
    {
        return $this->hasMany(BitacoraIncidenteAlumno::class, 'incidente_id')
                    ->where('rol', 'testigo')
                    ->with('alumno.curso');
    }

    // Medidas restaurativas registradas
    public function medidas()
    {
        return $this->hasMany(MedidaRestaurativa::class, 'incidente_id');
    }

    // Documentos adjuntos (relación polimórfica)
    public function documentos()
    {
        return $this->hasMany(DocumentoAdjunto::class, 'entidad_id')
                    ->where('entidad', 'bitacora_incidentes');
    }

    // ---------------------------------------------------
    // SCOPES
    // ---------------------------------------------------
    public function scopeDelColegio($query, $id)
    {
        return $query->where('establecimiento_id', $id);
    }
}

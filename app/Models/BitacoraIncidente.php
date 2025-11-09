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
        'alumno_id',
        'curso_id',
        'accion_realizada',
        'reportado_por',
        'estado_id',
        'seguimiento_id',
        'establecimiento_id'
    ];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function reportadoPor()
    {
        return $this->belongsTo(Funcionario::class, 'reportado_por');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoIncidente::class, 'estado_id');
    }

    public function seguimiento()
    {
        return $this->belongsTo(SeguimientoEmocional::class, 'seguimiento_id');
    }

    public function medidas()
    {
        return $this->hasMany(MedidaRestaurativa::class, 'incidente_id');
    }

    public function scopeDelColegio($query, $id)
    {
        return $query->where('establecimiento_id', $id);
    }
}

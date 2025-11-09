<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CitacionApoderado extends Model
{
    use HasFactory;

    protected $table = 'citaciones_apoderados';

    protected $fillable = [
        'alumno_id',
        'apoderado_id',
        'funcionario_id',
        'fecha_citacion',
        'motivo',
        'estado_id',
        'observaciones',
        'establecimiento_id'
    ];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }

    public function apoderado()
    {
        return $this->belongsTo(Apoderado::class);
    }

    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class);
    }

    public function estado()
    {
        return $this->belongsTo(EstadoCitacion::class, 'estado_id');
    }

    public function scopeDelColegio($query, $id)
    {
        return $query->where('establecimiento_id', $id);
    }
}

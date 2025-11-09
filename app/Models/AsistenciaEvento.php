<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AsistenciaEvento extends Model
{
    use HasFactory;

    protected $table = 'asistencia_eventos';

    protected $fillable = [
        'alumno_id',
        'fecha',
        'hora',
        'tipo_id',
        'observaciones',
        'registrado_por',
        'establecimiento_id'
    ];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }

    public function tipo()
    {
        return $this->belongsTo(TipoAsistencia::class, 'tipo_id');
    }

    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class, 'registrado_por');
    }

    public function scopeDelColegio($query, $id)
    {
        return $query->where('establecimiento_id', $id);
    }
}

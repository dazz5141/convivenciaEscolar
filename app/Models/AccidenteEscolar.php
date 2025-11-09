<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccidenteEscolar extends Model
{
    use HasFactory;

    protected $table = 'accidentes_escolares';

    protected $fillable = [
        'fecha',
        'alumno_id',
        'tipo_accidente_id',
        'lugar',
        'descripcion',
        'atencion_inmediata',
        'derivacion_salud',
        'registrado_por',
        'establecimiento_id'
    ];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }

    public function tipo()
    {
        return $this->belongsTo(TipoAccidente::class, 'tipo_accidente_id');
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedidaRestaurativa extends Model
{
    use HasFactory;

    protected $table = 'medidas_restaurativas';

    protected $fillable = [
        'alumno_id',
        'incidente_id',
        'tipo_medida_id',
        'fecha_inicio',
        'fecha_fin',
        'responsable_funcionario',
        'cumplimiento_estado_id',
        'observaciones',
        'establecimiento_id'
    ];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }

    public function incidente()
    {
        return $this->belongsTo(BitacoraIncidente::class, 'incidente_id');
    }

    public function tipoMedida()
    {
        return $this->belongsTo(TipoMedidaRestaurativa::class, 'tipo_medida_id');
    }

    public function responsable()
    {
        return $this->belongsTo(Funcionario::class, 'responsable_funcionario');
    }

    public function cumplimiento()
    {
        return $this->belongsTo(EstadoCumplimiento::class, 'cumplimiento_estado_id');
    }

    public function scopeDelColegio($query, $id)
    {
        return $query->where('establecimiento_id', $id);
    }
}

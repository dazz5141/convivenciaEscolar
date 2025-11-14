<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NovedadInspectoria extends Model
{
    use HasFactory;

    protected $table = 'novedades_inspectoria';

    protected $fillable = [
        'fecha',
        'funcionario_id',
        'alumno_id',
        'curso_id',
        'tipo_novedad_id',
        'descripcion',
        'establecimiento_id'
    ];

    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class);
    }

    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function tipo()
    {
        return $this->belongsTo(TipoNovedadInspectoria::class, 'tipo_novedad_id');
    }

    public function scopeDelColegio($query, $id)
    {
        return $query->where('establecimiento_id', $id);
    }
}

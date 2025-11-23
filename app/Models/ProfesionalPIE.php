<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProfesionalPIE extends Model
{
    use HasFactory;

    protected $table = 'profesionales_pie';

    protected $fillable = [
        'establecimiento_id',
        'funcionario_id',
        'tipo_id',
    ];

    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class);
    }

    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class);
    }

    public function tipo()
    {
        return $this->belongsTo(TipoProfesionalPIE::class, 'tipo_id');
    }

    public function intervenciones()
    {
        return $this->hasMany(IntervencionPIE::class, 'profesional_id');
    }

    public function cargo()
    {
        return $this->hasOneThrough(Cargo::class, Funcionario::class, 'id', 'id', 'funcionario_id', 'cargo_id');
    }

    public function getNombreCompletoAttribute()
    {
        if (!$this->funcionario) {
            return null;
        }

        return "{$this->funcionario->apellido_paterno} {$this->funcionario->apellido_materno}, {$this->funcionario->nombre}";
    }

    public function getUsuarioAttribute()
    {
        return $this->funcionario->usuario ?? null;
    }
}

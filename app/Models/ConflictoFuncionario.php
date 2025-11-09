<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ConflictoFuncionario extends Model
{
    use HasFactory;

    protected $table = 'conflictos_funcionarios';

    protected $fillable = [
        'fecha',
        'denunciante_id',
        'denunciado_id',
        'tipo_conflicto',
        'descripcion',
        'accion_tomada',
        'estado_id',
        'confidencial',
        'derivado_ley_karin',
        'establecimiento_id'
    ];

    public function denunciante()
    {
        return $this->belongsTo(Funcionario::class, 'denunciante_id');
    }

    public function denunciado()
    {
        return $this->belongsTo(Funcionario::class, 'denunciado_id');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoConflictoFuncionario::class, 'estado_id');
    }

    public function denunciasLeyKarin()
    {
        return $this->morphMany(DenunciaLeyKarin::class, 'conflictable');
    }

    public function scopeDelColegio($q, $id)
    {
        return $q->where('establecimiento_id', $id);
    }
}

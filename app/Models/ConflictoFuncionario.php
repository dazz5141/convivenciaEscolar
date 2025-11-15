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
        'funcionario_1_id',
        'funcionario_2_id',
        'registrado_por_id',
        'tipo_conflicto',
        'lugar_conflicto',
        'descripcion',
        'acuerdos_previos',
        'estado_id',
        'confidencial',
        'derivado_ley_karin',
        'establecimiento_id',
    ];

    // Relaciones
    public function funcionario1()
    {
        return $this->belongsTo(Funcionario::class, 'funcionario_1_id');
    }

    public function funcionario2()
    {
        return $this->belongsTo(Funcionario::class, 'funcionario_2_id');
    }

    public function registradoPor()
    {
        return $this->belongsTo(Usuario::class, 'registrado_por_id');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoConflictoFuncionario::class, 'estado_id');
    }

    // Relación futura con Denuncias Ley Karin
    public function denunciaLeyKarin()
    {
        return $this->hasOne(DenunciaLeyKarin::class, 'conflicto_funcionario_id');
    }

    public function scopeDelColegio($q, $id)
    {
        return $q->where('establecimiento_id', $id);
    }
}

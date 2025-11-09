<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ConflictoApoderado extends Model
{
    use HasFactory;

    protected $table = 'conflictos_apoderados';

    protected $fillable = [
        'fecha',
        'funcionario_id',
        'apoderado_nombre',
        'apoderado_rut',
        'tipo_conflicto',
        'descripcion',
        'accion_tomada',
        'estado_id',
        'confidencial',
        'derivado_ley_karin',
        'establecimiento_id'
    ];

    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class);
    }

    public function estado()
    {
        return $this->belongsTo(EstadoConflictoApoderado::class, 'estado_id');
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

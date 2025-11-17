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
        'registrado_por_id',

        // HÍBRIDO
        'apoderado_id',
        'apoderado_nombre',
        'apoderado_rut',

        'tipo_conflicto',
        'lugar_conflicto',
        'descripcion',
        'accion_tomada',
        'estado_id',
        'confidencial',
        'derivado_ley_karin',
        'establecimiento_id'
    ];

    // Funcionario que recibe el conflicto
    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class);
    }

    // Quien registra el caso
    public function registradoPor()
    {
        return $this->belongsTo(Funcionario::class, 'registrado_por_id');
    }

    // Apoderado interno
    public function apoderado()
    {
        return $this->belongsTo(Apoderado::class, 'apoderado_id');
    }

    // Estado del conflicto
    public function estado()
    {
        return $this->belongsTo(EstadoConflictoApoderado::class, 'estado_id');
    }

    // En caso de vincular con Denuncias Ley Karin
    public function denunciasLeyKarin()
    {
        return $this->morphMany(DenunciaLeyKarin::class, 'conflictable');
    }

    public function scopeDelColegio($q, $id)
    {
        return $q->where('establecimiento_id', $id);
    }
}

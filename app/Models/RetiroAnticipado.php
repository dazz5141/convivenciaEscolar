<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RetiroAnticipado extends Model
{
    use HasFactory;

    protected $table = 'retiros_anticipados';

    protected $fillable = [
        'alumno_id',
        'fecha',
        'hora',
        'motivo',
        'apoderado_id',          
        'nombre_retira',        
        'run_retira',
        'parentesco_retira',
        'telefono_retira',
        'entregado_por',
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

    public function funcionarioEntrega()
    {
        return $this->belongsTo(Funcionario::class, 'entregado_por');
    }

    public function scopeDelColegio($query, $id)
    {
        return $query->where('establecimiento_id', $id);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Auditoria extends Model
{
    use HasFactory;

    protected $table = 'auditorias';

    protected $fillable = [
        'usuario_id',
        'establecimiento_id',
        'accion',
        'modulo',
        'detalle'
    ];

    // Usuario responsable de la acción
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    // Colegio donde ocurre el evento
    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class);
    }

    // Scope multicolegio
    public function scopeDelColegio($query, $id)
    {
        return $query->where('establecimiento_id', $id);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EstadoSeguimientoEmocional extends Model
{
    use HasFactory;

    protected $table = 'estados_seguimiento_emocional';

    protected $fillable = [
        'nombre',
        'color',
    ];

    public function seguimientos()
    {
        return $this->hasMany(SeguimientoEmocional::class, 'estado_id');
    }
}

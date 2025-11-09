<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ModuloHabilitado extends Model
{
    use HasFactory;

    protected $table = 'modulos_habilitados';

    protected $fillable = [
        'establecimiento_id',
        'modulo',
        'activo'
    ];

    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class);
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', 1);
    }
}

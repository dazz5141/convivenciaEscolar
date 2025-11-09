<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Apoderado extends Model
{
    use HasFactory;

    protected $table = 'apoderados';

    protected $fillable = [
        'run',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'telefono',
        'email',
        'direccion',
        'activo'
    ];

    public function alumnos()
    {
        return $this->belongsToMany(Alumno::class, 'alumnos_apoderados')
                     ->withPivot('tipo');
    }

    public function retiros()
    {
        return $this->hasMany(RetiroAnticipado::class, 'apoderado_id');
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', 1);
    }
}

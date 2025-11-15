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
        'region_id',
        'provincia_id',
        'comuna_id',
        'establecimiento_id',   
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

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'provincia_id');
    }

    public function comuna()
    {
        return $this->belongsTo(Comuna::class, 'comuna_id');
    }

    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} {$this->apellido_paterno} {$this->apellido_materno}";
    }
}

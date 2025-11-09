<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Funcionario extends Model
{
    use HasFactory;

    protected $table = 'funcionarios';

    protected $fillable = [
        'run',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'cargo_id',
        'tipo_contrato_id',
        'establecimiento_id',
        'region_id',
        'provincia_id',
        'comuna_id',
        'direccion',
        'activo'
    ];

    public function cargo()
    {
        return $this->belongsTo(Cargo::class);
    }

    public function tipoContrato()
    {
        return $this->belongsTo(TipoContrato::class);
    }

    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class);
    }

    public function usuario()
    {
        return $this->hasOne(Usuario::class);
    }

    public function seguimientosEmocionales()
    {
        return $this->hasMany(SeguimientoEmocional::class, 'evaluado_por');
    }

    public function bitacoras()
    {
        return $this->hasMany(BitacoraIncidente::class, 'reportado_por');
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', 1);
    }
}

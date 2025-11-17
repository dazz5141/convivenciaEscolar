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

    protected $appends = ['nombre_completo'];

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

    public function scopeDelColegio($query, $id)
    {
        return $query->where('establecimiento_id', $id);
    }

    public function getNombreCompletoAttribute()
    {
        return trim("{$this->nombre} {$this->apellido_paterno} {$this->apellido_materno}");
    }
}

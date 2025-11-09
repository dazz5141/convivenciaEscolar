<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Usuario extends Model
{
    use HasFactory;

    protected $table = 'usuarios';

    protected $fillable = [
        'email',
        'password',
        'rol_id',
        'funcionario_id',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'avatar',
        'activo'
    ];

    protected $hidden = ['password'];

    public function rol()
    {
        return $this->belongsTo(Rol::class);
    }

    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class);
    }

    public function auditorias()
    {
        return $this->hasMany(Auditoria::class);
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', 1);
    }
}

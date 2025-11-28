<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';

    protected $appends = ['nombre_completo'];

    protected $fillable = [
        'email',
        'password',
        'rol_id',
        'funcionario_id',
        'establecimiento_id',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'avatar',
        'activo'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class, 'funcionario_id');
    }

    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class, 'establecimiento_id');
    }

    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class, 'usuario_id');
    }

    public function auditorias()
    {
        return $this->hasMany(Auditoria::class, 'usuario_id');
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', 1);
    }

    public function getNombreCompletoAttribute()
    {
        $nombre = $this->nombre ?? '';
        $ap = $this->apellido_paterno ?? '';
        $am = $this->apellido_materno ?? '';

        return trim("$nombre $ap $am");
    }

    public function hasRole($roles)
    {
        // Si envías un array → revisa si el rol_id está dentro
        if (is_array($roles)) {
            return in_array($this->rol_id, $roles);
        }

        // Si envías un solo ID de rol
        return $this->rol_id == $roles;
    }
}

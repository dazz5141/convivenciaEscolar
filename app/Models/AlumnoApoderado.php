<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlumnoApoderado extends Model
{
    protected $table = 'alumnos_apoderados';

    protected $fillable = [
        'alumno_id',
        'apoderado_id',
        'tipo',
    ];

    public $timestamps = false;

    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }

    public function apoderado()
    {
        return $this->belongsTo(Apoderado::class);
    }
}

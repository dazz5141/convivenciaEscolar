<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipoAsistencia extends Model
{
    use HasFactory;

    protected $table = 'tipos_asistencia';

    protected $fillable = ['nombre'];
}

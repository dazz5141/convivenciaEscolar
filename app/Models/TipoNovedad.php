<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipoNovedad extends Model
{
    use HasFactory;

    protected $table = 'tipos_novedad';

    protected $fillable = ['nombre'];
}

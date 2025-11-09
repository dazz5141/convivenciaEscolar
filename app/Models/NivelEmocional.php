<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NivelEmocional extends Model
{
    use HasFactory;

    protected $table = 'niveles_emocionales';

    protected $fillable = ['nombre'];
}

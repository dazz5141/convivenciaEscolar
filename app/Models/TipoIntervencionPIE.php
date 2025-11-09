<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipoIntervencionPIE extends Model
{
    use HasFactory;

    protected $table = 'tipos_intervencion_pie';

    protected $fillable = ['nombre'];
}

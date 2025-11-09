<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipoMedidaRestaurativa extends Model
{
    use HasFactory;

    protected $table = 'tipos_medida_restaurativa';

    protected $fillable = ['nombre'];
}

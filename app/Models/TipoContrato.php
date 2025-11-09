<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipoContrato extends Model
{
    use HasFactory;

    protected $table = 'tipo_contratos';

    protected $fillable = ['nombre'];
}

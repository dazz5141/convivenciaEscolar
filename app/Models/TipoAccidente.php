<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipoAccidente extends Model
{
    use HasFactory;

    protected $table = 'tipos_accidente';

    protected $fillable = ['nombre'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EstadoIncidente extends Model
{
    use HasFactory;

    protected $table = 'estados_incidente';

    protected $fillable = ['nombre'];
}

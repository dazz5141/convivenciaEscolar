<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EstadoCitacion extends Model
{
    use HasFactory;

    protected $table = 'estados_citacion';

    protected $fillable = ['nombre'];
}

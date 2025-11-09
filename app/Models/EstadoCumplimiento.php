<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EstadoCumplimiento extends Model
{
    use HasFactory;

    protected $table = 'estados_cumplimiento';

    protected $fillable = ['nombre'];
}

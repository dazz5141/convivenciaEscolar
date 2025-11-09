<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EstadoConflictoApoderado extends Model
{
    use HasFactory;

    protected $table = 'estados_conflicto_apoderado';

    protected $fillable = ['nombre'];
}

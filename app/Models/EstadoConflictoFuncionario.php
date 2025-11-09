<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EstadoConflictoFuncionario extends Model
{
    use HasFactory;

    protected $table = 'estados_conflicto_funcionario';

    protected $fillable = ['nombre'];
}

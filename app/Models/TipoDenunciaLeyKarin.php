<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipoDenunciaLeyKarin extends Model
{
    use HasFactory;

    protected $table = 'tipos_denuncia_ley_karin';

    protected $fillable = ['nombre'];
}

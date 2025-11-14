<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoNovedadInspectoria extends Model
{
    use HasFactory;

    protected $table = 'tipos_novedad_inspectoria';

    protected $fillable = [
        'nombre',
    ];
}

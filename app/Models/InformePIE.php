<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InformePIE extends Model
{
    use HasFactory;

    protected $table = 'informes_pie';

    protected $fillable = [
        'establecimiento_id',
        'estudiante_pie_id',
        'fecha',
        'tipo',
        'contenido',
    ];

    public function estudiante()
    {
        return $this->belongsTo(EstudiantePIE::class, 'estudiante_pie_id');
    }
}

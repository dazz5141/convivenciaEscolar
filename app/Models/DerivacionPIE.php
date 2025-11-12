<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DerivacionPIE extends Model
{
    use HasFactory;

    protected $table = 'derivaciones_pie';

    protected $fillable = [
        'establecimiento_id',
        'estudiante_pie_id',
        'fecha',
        'destino',
        'motivo',
        'estado',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function estudiante()
    {
        return $this->belongsTo(EstudiantePIE::class, 'estudiante_pie_id');
    }

    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class);
    }
}

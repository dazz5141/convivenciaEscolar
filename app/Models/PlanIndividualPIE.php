<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlanIndividualPIE extends Model
{
    use HasFactory;

    protected $table = 'planes_individuales_pie';

    protected $fillable = [
        'establecimiento_id',
        'estudiante_pie_id',
        'fecha_inicio',
        'fecha_termino',
        'objetivos',
        'evaluacion',
    ];

    protected $casts = [
        'fecha_inicio'  => 'date',
        'fecha_termino' => 'date',
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

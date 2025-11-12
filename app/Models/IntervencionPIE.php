<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IntervencionPIE extends Model
{
    use HasFactory;

    protected $table = 'intervenciones_pie';

    protected $fillable = [
        'establecimiento_id',
        'estudiante_pie_id',
        'tipo_intervencion_id',
        'profesional_id',
        'fecha',
        'detalle',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class);
    }

    public function estudiante()
    {
        return $this->belongsTo(EstudiantePIE::class, 'estudiante_pie_id');
    }

    public function tipo()
    {
        return $this->belongsTo(TipoIntervencionPIE::class, 'tipo_intervencion_id');
    }

    public function profesional()
    {
        return $this->belongsTo(ProfesionalPIE::class, 'profesional_id');
    }
}

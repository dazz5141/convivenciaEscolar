<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EstudiantePIE extends Model
{
    use HasFactory;

    protected $table = 'estudiantes_pie';

    protected $fillable = [
        'establecimiento_id',
        'alumno_id',
        'fecha_ingreso',
        'fecha_egreso',
        'diagnostico',
        'observaciones',
    ];

    protected $casts = [
        'fecha_ingreso' => 'date',
        'fecha_egreso'  => 'date',
    ];

    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class);
    }

    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'alumno_id', 'id');
    }


    public function intervenciones()
    {
        return $this->hasMany(IntervencionPIE::class, 'estudiante_pie_id');
    }

    public function planes()
    {
        return $this->hasMany(PlanIndividualPIE::class, 'estudiante_pie_id');
    }

    public function derivaciones()
    {
        return $this->hasMany(DerivacionPIE::class, 'estudiante_pie_id');
    }

    // -------------------------------
    // RELACIÓN PIE
    // -------------------------------

    public function informes()
    {
        return $this->hasMany(InformePIE::class, 'estudiante_pie_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DenunciaLeyKarin extends Model
{
    use HasFactory;

    protected $table = 'denuncias_ley_karin';

    protected $fillable = [
        'conflictable_type',
        'conflictable_id',
        'es_victima',
        'confidencial',
        'tipo_acoso',
        'tipo_laboral',
        'tipo_violencia',
        'denunciante_nombre',
        'denunciante_cargo',
        'denunciante_area',
        'victima_nombre',
        'victima_rut',
        'victima_direccion',
        'victima_comuna',
        'victima_region',
        'victima_telefono',
        'victima_email',
        'denunciado_nombre',
        'denunciado_cargo',
        'denunciado_area',
        'jerarquia',
        'es_jefatura_inmediata',
        'trabaja_directamente',
        'denunciante_informo_superior',
        'narracion',
        'tiempo_afectacion',
        'individualizacion_agresores',
        'testigos',
        'evidencia_testigos',
        'evidencia_correos',
        'evidencia_fotos',
        'evidencia_videos',
        'evidencia_otros',
        'evidencia_otros_detalle',
        'observaciones',
        'fecha_firma'
    ];

    public function conflictable()
    {
        return $this->morphTo();
    }

    public function tipos()
    {
        return $this->hasMany(DenunciaLeyKarinTipo::class, 'denuncia_id');
    }
}

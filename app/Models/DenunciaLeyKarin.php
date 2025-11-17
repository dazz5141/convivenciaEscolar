<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DenunciaLeyKarin extends Model
{
    use HasFactory;

    protected $table = 'denuncias_ley_karin';

    protected $fillable = [
        'establecimiento_id',
        'registrado_por_id',
        'fecha_denuncia',
        'descripcion',

        // Polimórfico
        'conflictable_type',
        'conflictable_id',

        // Datos generales
        'es_victima',
        'confidencial',

        // NUEVO: FK tipo
        'tipo_denuncia_id',

        // Denunciante
        'denunciante_nombre',
        'denunciante_cargo',
        'denunciante_area',
        'denunciante_rut',

        // Víctima
        'victima_nombre',
        'victima_rut',
        'victima_direccion',
        'victima_comuna',
        'victima_region',
        'victima_telefono',
        'victima_email',

        // Denunciado
        'denunciado_nombre',
        'denunciado_cargo',
        'denunciado_area',
        'denunciado_rut',

        // Otros campos legales
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

    public function tipo()
    {
        return $this->belongsTo(TipoDenunciaLeyKarin::class, 'tipo_denuncia_id');
    }

    public function documentos()
    {
        return $this->morphMany(DocumentoAdjunto::class, 'entidad');
    }

    public function registradoPor()
    {
        return $this->belongsTo(Funcionario::class, 'registrado_por_id');
    }
}

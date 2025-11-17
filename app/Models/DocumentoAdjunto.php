<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentoAdjunto extends Model
{
    use HasFactory;

    protected $table = 'documentos_adjuntos';

    protected $fillable = [
        'entidad_type',
        'entidad_id',
        'nombre_archivo',
        'ruta_archivo',
        'subido_por',
        'establecimiento_id'
    ];

    public function entidad()
    {
        return $this->morphTo();
    }

    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class, 'subido_por');
    }

    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class);
    }

    public function scopeDelColegio($query, $id)
    {
        return $query->where('establecimiento_id', $id);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DenunciaLeyKarinTipo extends Model
{
    use HasFactory;

    protected $table = 'denuncia_ley_karin_tipo';

    protected $fillable = [
        'denuncia_id',
        'tipo_id'
    ];

    public function denuncia()
    {
        return $this->belongsTo(DenunciaLeyKarin::class, 'denuncia_id');
    }

    public function tipo()
    {
        return $this->belongsTo(TipoDenunciaLeyKarin::class, 'tipo_id');
    }
}

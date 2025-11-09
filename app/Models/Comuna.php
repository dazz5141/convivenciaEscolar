<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comuna extends Model
{
    use HasFactory;

    protected $table = 'comunas';

    protected $fillable = ['provincia_id','nombre'];

    public function provincia()
    {
        return $this->belongsTo(Provincia::class);
    }
}

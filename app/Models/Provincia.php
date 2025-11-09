<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Provincia extends Model
{
    use HasFactory;

    protected $table = 'provincias';

    protected $fillable = ['region_id','nombre'];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function comunas()
    {
        return $this->hasMany(Comuna::class);
    }
}

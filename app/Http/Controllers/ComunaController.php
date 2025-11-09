<?php

namespace App\Http\Controllers;

use App\Models\Comuna;

class ComunaController extends Controller
{
    public function porProvincia($provincia_id)
    {
        return Comuna::where('provincia_id', $provincia_id)->get();
    }
}

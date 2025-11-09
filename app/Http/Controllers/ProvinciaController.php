<?php

namespace App\Http\Controllers;

use App\Models\Provincia;

class ProvinciaController extends Controller
{
    public function porRegion($region_id)
    {
        return Provincia::where('region_id', $region_id)->get();
    }
}

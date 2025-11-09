<?php

namespace App\Http\Controllers;

use App\Models\Region;

class RegionController extends Controller
{
    public function index()
    {
        return Region::all();
    }
}

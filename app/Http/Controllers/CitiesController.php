<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;

class CitiesController extends Controller
{
    public function index()
    {
        $cities = City::with('county')->get();
        return response()->json(compact('cities'));
    }
}

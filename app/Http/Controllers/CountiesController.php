<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\County;

class CountiesController extends Controller
{
    public function index()
    {
        $counties = County::all();
        return response()->json(compact('counties'));
    }
}

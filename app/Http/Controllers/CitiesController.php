<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Models\City;

class CitiesController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $countyId = $request->input('county_id');
        $cities = City::whereHas('county', function ($query) use ($countyId) {
            $query->when($countyId, function (Builder $query, string $id) {
                $query->where('id', '=', $id);
            });
        })->when($id, function (Builder $query, string $id) {
                $query->where('id', '=', $id);
            })
            ->when($name, function (Builder $query, string $name) {
                $query->where('city', 'LIKE', '%' . $name . '%');
            })
            ->with('county')
            ->orderBy('id')
            ->get();
        return response()->json(compact('cities'));
    }
}

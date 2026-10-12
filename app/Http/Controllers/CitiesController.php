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

    public function store(Request $request)
    {
        $request->validate([
            'zip_code' => 'required|integer|min:1000|max:9999',
            'city' => 'required|min:1',
            'county_id' => 'required|exists:counties,id',
            'population' => 'required|integer|min:0',
        ]);
        $city = City::create($request->all());
        return response()->json(compact('city'));
    }
}

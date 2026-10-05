<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Models\County;

class CountiesController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $counties = County::query()
            ->when($id, function (Builder $query, string $id) {
                $query->where('id', '=', $id);
            })
            ->when($name, function (Builder $query, string $name) {
                $query->where('name', 'LIKE', '%' . $name . '%');
            })
            ->orderBy('counties.name')
            ->get();
        return response()->json(compact('counties'));
    }
}

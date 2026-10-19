<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Models\City;
use App\Http\Requests\CityRequest;

/**
 * @apiDefine City
 *
 * @apiSuccess {Object} city The city resource.
 * @apiSuccess {Integer} city.id The unique city identifier.
 * @apiSuccess {Integer} city.zip_code The four-digit postal code.
 * @apiSuccess {String} city.city The city name.
 * @apiSuccess {Integer} city.county_id The associated county identifier.
 * @apiSuccess {Integer} city.population The city population.
 */

/**
 * @apiDefine ValidationError
 *
 * @apiError (400) ValidationError The request contains invalid data.
 *
 * @apiErrorExample {json} Validation error response:
 * HTTP/1.1 422 Unprocessable Content
 * {
 *   "message": "The given data was invalid.",
 *   "errors": {
 *     "zip_code": [
 *       "The zip code field is required."
 *     ]
 *   }
 * }
 */

/**
 * @api {get} /api/cities List cities
 * @apiName GetCities
 * @apiGroup Cities
 * @apiVersion 1.0.0
 *
 * @apiDescription Returns cities filtered by the supplied parameters.
 * Results are ordered alphabetically by city name. Each city includes
 * its related county, provided the City model relationship is configured.
 *
 * The county_id parameter is applied unconditionally by the controller.
 * Supply it to retrieve cities belonging to a specific county.
 *
 * @apiQuery {Integer} county_id County identifier. Required by the
 * controller query to obtain matching results.
 * @apiQuery {Integer} [id] Filter by exact city ID.
 * @apiQuery {String} [name] Filter by a partial city name match.
 *
 * @apiSuccess {Object[]} cities List of matching cities.
 * @apiSuccess {Integer} cities.id City identifier.
 * @apiSuccess {Integer} cities.zip_code Postal code.
 * @apiSuccess {String} cities.city City name.
 * @apiSuccess {Integer} cities.county_id County identifier.
 * @apiSuccess {Integer} cities.population Population.
 * @apiSuccess {Object} cities.county Related county object.
 *
 * @apiSuccessExample {json} Success response:
 * HTTP/1.1 200 OK
 * {
 *   "cities": [
 *     {
 *       "id": 1,
 *       "zip_code": 1234,
 *       "city": "Example City",
 *       "county_id": 2,
 *       "population": 25000,
 *       "county": {
 *         "id": 2,
 *         "name": "Example County"
 *       }
 *     }
 *   ]
 * }
 *
 * Note: The county object's exact structure depends on the County model.
 *
 * @apiExample {curl} Example request:
 * curl -X GET "https://example.com/api/cities?county_id=2&name=Example"
 */

/**
 * @api {post} /api/cities Create a city
 * @apiName CreateCity
 * @apiGroup Cities
 * @apiVersion 1.0.0
 *
 * @apiDescription Creates a city using the submitted request data.
 * The request is validated by CityRequest before the city is created.
 *
 * @apiBody {Integer} zip_code Four-digit postal code. Required.
 * @apiBody {String} city City name. Required; minimum length 1.
 * @apiBody {Integer} county_id Existing county ID. Required.
 * @apiBody {Integer} population Population. Required; minimum value 0.
 *
 * @apiUse City
 *
 * @apiSuccessExample {json} Success response:
 * HTTP/1.1 200 OK
 * {
 *   "city": {
 *     "id": 1,
 *     "zip_code": 1234,
 *     "city": "Example City",
 *     "county_id": 2,
 *     "population": 25000
 *   }
 * }
 *
 * @apiUse ValidationError
 *
 * @apiExample {curl} Example request:
 * curl -X POST "https://example.com/api/cities" \
 *   -H "Content-Type: application/json" \
 *   -H "Accept: application/json" \
 *   -d '{
 *     "zip_code": 1234,
 *     "city": "Example City",
 *     "county_id": 2,
 *     "population": 25000
 *   }'
 */

/**
 * @api {put} /api/cities/:id Update a city
 * @apiName UpdateCity
 * @apiGroup Cities
 * @apiVersion 1.0.0
 *
 * @apiDescription Updates an existing city. All fields defined by
 * CityRequest are required, including when updating only one field.
 * Returns a 404 response if the city does not exist.
 *
 * @apiParam {Integer} id City identifier.
 *
 * @apiBody {Integer} zip_code Four-digit postal code. Required.
 * @apiBody {String} city City name. Required; minimum length 1.
 * @apiBody {Integer} county_id Existing county ID. Required.
 * @apiBody {Integer} population Population. Required; minimum value 0.
 *
 * @apiUse City
 *
 * @apiSuccessExample {json} Success response:
 * HTTP/1.1 200 OK
 * {
 *   "city": {
 *     "id": 1,
 *     "zip_code": 1234,
 *     "city": "Updated City",
 *     "county_id": 2,
 *     "population": 30000
 *   }
 * }
 *
 * @apiUse ValidationError
 *
 * @apiError (404) NotFound The specified city does not exist.
 *
 * @apiExample {curl} Example request:
 * curl -X PUT "https://example.com/api/cities/1" \
 *   -H "Content-Type: application/json" \
 *   -H "Accept: application/json" \
 *   -d '{
 *     "zip_code": 1234,
 *     "city": "Updated City",
 *     "county_id": 2,
 *     "population": 30000
 *   }'
 */

/**
 * @api {delete} /api/cities/:id Delete a city
 * @apiName DeleteCity
 * @apiGroup Cities
 * @apiVersion 1.0.0
 *
 * @apiDescription Deletes the specified city and returns the deleted
 * model as JSON. Returns a 404 response if the city does not exist.
 *
 * @apiParam {Integer} id City identifier.
 *
 * @apiSuccess {Object} city The deleted city resource.
 * @apiSuccess {Integer} city.id City identifier.
 * @apiSuccess {Integer} city.zip_code Postal code.
 * @apiSuccess {String} city.city City name.
 * @apiSuccess {Integer} city.county_id County identifier.
 * @apiSuccess {Integer} city.population Population.
 *
 * @apiSuccessExample {json} Success response:
 * HTTP/1.1 200 OK
 * {
 *   "city": {
 *     "id": 1,
 *     "zip_code": 1234,
 *     "city": "Example City",
 *     "county_id": 2,
 *     "population": 25000
 *   }
 * }
 *
 * @apiError (404) NotFound The specified city does not exist.
 *
 * @apiExample {curl} Example request:
 * curl -X DELETE "https://example.com/api/cities/1" \
 *   -H "Accept: application/json"
 */

class CitiesController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $countyId = $request->input('county_id');
        $cities = City::query()
            ->when($id, function (Builder $query, string $id) {
                $query->where('id', '=', $id);
            })
            ->when($name, function (Builder $query, string $name) {
                $query->where('city', 'LIKE', '%' . $name . '%');
            })
            ->where('county_id', '=', $countyId)
            ->with('county')
            ->orderBy('city')
            ->get();
        return response()->json(compact('cities'));
    }

    public function store(CityRequest $request)
    {
        $city = City::create($request->all());
        return response()->json(compact('city'));
    }

    public function update(CityRequest $request, $id)
    {
        $city = City::findOrFail($id);
        $city->update($request->all());
        return response()->json(compact('city'));
    }

    public function destroy($id)
    {
        $city = City::findOrFail($id);
        $city->delete();
        return response()->json(compact('city'));
    }
}

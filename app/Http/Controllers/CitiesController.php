<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Models\City;
use App\Http\Requests\CityRequest;

/**
 * @apiDefine CityValidationError
 *
 * @apiError (422) UnprocessableEntity Request validation failed.
 *
 * @apiErrorExample {json} Validation error:
 * HTTP/1.1 422 Unprocessable Entity
 * {
 *   "message": "The given data was invalid.",
 *   "errors": {
 *     "zip_code": ["The zip code field is required."]
 *   }
 * }
 */

/**
 * @api {get} /api/cities List cities
 * @apiName GetCities
 * @apiGroup Cities
 * @apiVersion 1.0.0
 *
 * @apiDescription Returns cities filtered by county ID and optionally
 * by city ID or partial city name. Results are ordered by city name.
 *
 * @apiQuery {Integer} county_id County identifier used to filter results.
 * @apiQuery {Integer} [id] Filter by exact city ID.
 * @apiQuery {String} [name] Filter by partial city name.
 *
 * @apiSuccess {Object[]} cities Matching city resources.
 * @apiSuccess {Integer} cities.id City identifier.
 * @apiSuccess {Integer} cities.zip_code Four-digit postal code.
 * @apiSuccess {String} cities.city City name.
 * @apiSuccess {Integer} cities.county_id County identifier.
 * @apiSuccess {Integer} cities.population Population.
 * @apiSuccess {Object} cities.county Related county object.
 *
 * @apiSuccessExample {json} Success:
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
 *         "name": "Example County",
 *         "arms": "https://example.com/arms.png"
 *       }
 *     }
 *   ]
 * }
 *
 * Note: The county object structure is illustrative and depends on
 * the County model.
 *
 * @apiExample {curl} Example request:
 * curl -X GET \
 *   "https://example.com/api/cities?county_id=2&name=Example" \
 *   -H "Accept: application/json"
 */

/**
 * @api {post} /api/cities Create a city
 * @apiName CreateCity
 * @apiGroup Cities
 * @apiVersion 1.0.0
 *
 * @apiDescription Creates a city. Sanctum authentication is required.
 *
 * @apiHeader {String} Authorization Sanctum bearer token.
 * @apiHeaderExample {String} Authorization header:
 * Authorization: Bearer YOUR_ACCESS_TOKEN
 *
 * @apiBody {Integer} zip_code Four-digit postal code. Required.
 * @apiBody {String} city City name. Required; minimum length 1.
 * @apiBody {Integer} county_id Existing county ID. Required.
 * @apiBody {Integer} population Population. Required; minimum 0.
 *
 * @apiSuccess {Object} city Created city resource.
 * @apiSuccess {Integer} city.id City identifier.
 * @apiSuccess {Integer} city.zip_code Postal code.
 * @apiSuccess {String} city.city City name.
 * @apiSuccess {Integer} city.county_id County identifier.
 * @apiSuccess {Integer} city.population Population.
 *
 * @apiSuccessExample {json} Success:
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
 * @apiError (401) Unauthorized Missing or invalid authentication token.
 * @apiErrorExample {json} Unauthenticated:
 * HTTP/1.1 401 Unauthorized
 * {
 *   "message": "Unauthenticated."
 * }
 *
 * @apiUse CityValidationError
 *
 * @apiExample {curl} Example request:
 * curl -X POST "https://example.com/api/cities" \
 *   -H "Authorization: Bearer YOUR_ACCESS_TOKEN" \
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
 * @apiDescription Updates an existing city. Sanctum authentication
 * is required. All validated fields must be provided.
 *
 * @apiHeader {String} Authorization Sanctum bearer token.
 * @apiHeaderExample {String} Authorization header:
 * Authorization: Bearer YOUR_ACCESS_TOKEN
 *
 * @apiParam {Integer} id City identifier.
 *
 * @apiBody {Integer} zip_code Four-digit postal code. Required.
 * @apiBody {String} city City name. Required; minimum length 1.
 * @apiBody {Integer} county_id Existing county ID. Required.
 * @apiBody {Integer} population Population. Required; minimum 0.
 *
 * @apiSuccess {Object} city Updated city resource.
 * @apiSuccess {Integer} city.id City identifier.
 * @apiSuccess {Integer} city.zip_code Postal code.
 * @apiSuccess {String} city.city City name.
 * @apiSuccess {Integer} city.county_id County identifier.
 * @apiSuccess {Integer} city.population Population.
 *
 * @apiSuccessExample {json} Success:
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
 * @apiError (401) Unauthorized Missing or invalid authentication token.
 * @apiError (404) NotFound The specified city does not exist.
 * @apiUse CityValidationError
 *
 * @apiExample {curl} Example request:
 * curl -X PUT "https://example.com/api/cities/1" \
 *   -H "Authorization: Bearer YOUR_ACCESS_TOKEN" \
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
 * @apiDescription Deletes a city and returns the deleted model.
 * Sanctum authentication is required.
 *
 * @apiHeader {String} Authorization Sanctum bearer token.
 * @apiHeaderExample {String} Authorization header:
 * Authorization: Bearer YOUR_ACCESS_TOKEN
 *
 * @apiParam {Integer} id City identifier.
 *
 * @apiSuccess {Object} city Deleted city resource.
 * @apiSuccess {Integer} city.id City identifier.
 * @apiSuccess {Integer} city.zip_code Postal code.
 * @apiSuccess {String} city.city City name.
 * @apiSuccess {Integer} city.county_id County identifier.
 * @apiSuccess {Integer} city.population Population.
 *
 * @apiSuccessExample {json} Success:
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
 * @apiError (401) Unauthorized Missing or invalid authentication token.
 * @apiError (404) NotFound The specified city does not exist.
 *
 * @apiExample {curl} Example request:
 * curl -X DELETE "https://example.com/api/cities/1" \
 *   -H "Authorization: Bearer YOUR_ACCESS_TOKEN" \
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

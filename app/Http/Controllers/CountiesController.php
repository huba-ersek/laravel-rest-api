<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Models\County;

/**
 * @apiDefine CountyValidationError
 *
 * @apiError (422) UnprocessableEntity Request validation failed.
 *
 * @apiErrorExample {json} Validation error:
 * HTTP/1.1 422 Unprocessable Entity
 * {
 *   "message": "The given data was invalid.",
 *   "errors": {
 *     "name": ["The name field is required."],
 *     "arms": ["The arms field is required."]
 *   }
 * }
 */

/**
 * @api {get} /api/counties List counties
 * @apiName GetCounties
 * @apiGroup Counties
 * @apiVersion 1.0.0
 *
 * @apiDescription Returns counties optionally filtered by ID or
 * partial name. Results are ordered alphabetically by name.
 *
 * @apiQuery {Integer} [id] Filter by exact county ID.
 * @apiQuery {String} [name] Filter by partial county name.
 *
 * @apiSuccess {Object[]} counties List of county resources.
 * @apiSuccess {Integer} counties.id County identifier.
 * @apiSuccess {String} counties.name County name.
 * @apiSuccess {String} counties.arms County arms URL.
 *
 * @apiSuccessExample {json} Success:
 * HTTP/1.1 200 OK
 * {
 *   "counties": [
 *     {
 *       "id": 1,
 *       "name": "Example County",
 *       "arms": "https://example.com/images/example-county.png"
 *     }
 *   ]
 * }
 *
 * @apiExample {curl} Example request:
 * curl -X GET \
 *   "https://example.com/api/counties?name=Example" \
 *   -H "Accept: application/json"
 */

/**
 * @api {post} /api/counties Create a county
 * @apiName CreateCounty
 * @apiGroup Counties
 * @apiVersion 1.0.0
 *
 * @apiDescription Creates a county after validating the request.
 * Sanctum authentication is required.
 *
 * @apiHeader {String} Authorization Sanctum bearer token.
 * @apiHeaderExample {String} Authorization header:
 * Authorization: Bearer YOUR_ACCESS_TOKEN
 *
 * @apiBody {String} name County name. Required; minimum length 1.
 * @apiBody {String} arms HTTP or HTTPS URL. Required.
 *
 * @apiSuccess {Object} county Created county resource.
 * @apiSuccess {Integer} county.id County identifier.
 * @apiSuccess {String} county.name County name.
 * @apiSuccess {String} county.arms County arms URL.
 *
 * @apiSuccessExample {json} Success:
 * HTTP/1.1 200 OK
 * {
 *   "county": {
 *     "id": 1,
 *     "name": "Example County",
 *     "arms": "https://example.com/images/example-county.png"
 *   }
 * }
 *
 * @apiError (401) Unauthorized Missing or invalid authentication token.
 * @apiUse CountyValidationError
 *
 * @apiExample {curl} Example request:
 * curl -X POST "https://example.com/api/counties" \
 *   -H "Authorization: Bearer YOUR_ACCESS_TOKEN" \
 *   -H "Content-Type: application/json" \
 *   -H "Accept: application/json" \
 *   -d '{
 *     "name": "Example County",
 *     "arms": "https://example.com/images/example-county.png"
 *   }'
 */

/**
 * @api {put} /api/counties/:id Update a county
 * @apiName UpdateCounty
 * @apiGroup Counties
 * @apiVersion 1.0.0
 *
 * @apiDescription Updates an existing county after validating the
 * request. Sanctum authentication is required. Both fields are required.
 *
 * @apiHeader {String} Authorization Sanctum bearer token.
 * @apiHeaderExample {String} Authorization header:
 * Authorization: Bearer YOUR_ACCESS_TOKEN
 *
 * @apiParam {Integer} id County identifier.
 *
 * @apiBody {String} name County name. Required; minimum length 1.
 * @apiBody {String} arms HTTP or HTTPS URL. Required.
 *
 * @apiSuccess {Object} county Updated county resource.
 * @apiSuccess {Integer} county.id County identifier.
 * @apiSuccess {String} county.name County name.
 * @apiSuccess {String} county.arms County arms URL.
 *
 * @apiSuccessExample {json} Success:
 * HTTP/1.1 200 OK
 * {
 *   "county": {
 *     "id": 1,
 *     "name": "Updated County",
 *     "arms": "https://example.com/images/updated-county.png"
 *   }
 * }
 *
 * @apiError (401) Unauthorized Missing or invalid authentication token.
 * @apiError (404) NotFound The specified county does not exist.
 * @apiUse CountyValidationError
 *
 * @apiExample {curl} Example request:
 * curl -X PUT "https://example.com/api/counties/1" \
 *   -H "Authorization: Bearer YOUR_ACCESS_TOKEN" \
 *   -H "Content-Type: application/json" \
 *   -H "Accept: application/json" \
 *   -d '{
 *     "name": "Updated County",
 *     "arms": "https://example.com/images/updated-county.png"
 *   }'
 */

/**
 * @api {delete} /api/counties/:id Delete a county
 * @apiName DeleteCounty
 * @apiGroup Counties
 * @apiVersion 1.0.0
 *
 * @apiDescription Deletes an existing county and returns the deleted
 * model. Sanctum authentication is required.
 *
 * @apiHeader {String} Authorization Sanctum bearer token.
 * @apiHeaderExample {String} Authorization header:
 * Authorization: Bearer YOUR_ACCESS_TOKEN
 *
 * @apiParam {Integer} id County identifier.
 *
 * @apiSuccess {Object} county Deleted county resource.
 * @apiSuccess {Integer} county.id County identifier.
 * @apiSuccess {String} county.name County name.
 * @apiSuccess {String} county.arms County arms URL.
 *
 * @apiSuccessExample {json} Success:
 * HTTP/1.1 200 OK
 * {
 *   "county": {
 *     "id": 1,
 *     "name": "Example County",
 *     "arms": "https://example.com/images/example-county.png"
 *   }
 * }
 *
 * @apiError (401) Unauthorized Missing or invalid authentication token.
 * @apiError (404) NotFound The specified county does not exist.
 *
 * @apiExample {curl} Example request:
 * curl -X DELETE "https://example.com/api/counties/1" \
 *   -H "Authorization: Bearer YOUR_ACCESS_TOKEN" \
 *   -H "Accept: application/json"
 */

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

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:1',
            'arms' => 'required|url:http,https',
        ]);
        $county = County::create($request->all());
        return response()->json(compact('county'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|min:1',
            'arms' => 'required|url:http,https',
        ]);
        $county = County::findOrFail($id);
        $county->update($request->all());
        return response()->json(compact('county'));
    }

    public function destroy($id)
    {
        $county = County::findOrFail($id);
        $county->delete();
        return response()->json(compact('county'));
    }
}

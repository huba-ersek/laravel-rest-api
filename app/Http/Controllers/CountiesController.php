<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Models\County;

/**
 * @apiDefine CountyObject
 *
 * @apiSuccess {Object} county The county resource.
 * @apiSuccess {Integer} county.id The unique county identifier.
 * @apiSuccess {String} county.name The county name.
 * @apiSuccess {String} county.arms The county's arms URL.
 */

/**
 * @apiDefine CountyValidationError
 *
 * @apiError (422) UnprocessableEntity The request data failed validation.
 *
 * @apiErrorExample {json} Validation error response:
 * HTTP/1.1 422 Unprocessable Entity
 * {
 *   "message": "The given data was invalid.",
 *   "errors": {
 *     "name": [
 *       "The name field is required."
 *     ],
 *     "arms": [
 *       "The arms field is required."
 *     ]
 *   }
 * }
 *
 * Note: The exact error messages depend on Laravel's validation
 * configuration and the submitted data.
 */

/**
 * @api {get} /api/counties List counties
 * @apiName GetCounties
 * @apiGroup Counties
 * @apiVersion 1.0.0
 *
 * @apiDescription Returns a list of counties, optionally filtered by
 * county ID and/or a partial county name. Results are ordered
 * alphabetically by county name.
 *
 * @apiQuery {Integer} [id] Filter by exact county ID.
 * @apiQuery {String} [name] Filter by a partial county name match.
 *
 * @apiSuccess {Object[]} counties List of matching counties.
 * @apiSuccess {Integer} counties.id The unique county identifier.
 * @apiSuccess {String} counties.name The county name.
 * @apiSuccess {String} counties.arms The county's arms URL.
 *
 * @apiSuccessExample {json} Success response:
 * HTTP/1.1 200 OK
 * {
 *   "counties": [
 *     {
 *       "id": 1,
 *       "name": "Example County",
 *       "arms": "https://example.com/images/example-county.png"
 *     },
 *     {
 *       "id": 2,
 *       "name": "Sample County",
 *       "arms": "https://example.com/images/sample-county.png"
 *     }
 *   ]
 * }
 *
 * @apiExample {curl} Example request:
 * curl -X GET \
 *   "https://example.com/api/counties?name=County" \
 *   -H "Accept: application/json"
 *
 * @apiExample {curl} Filter by ID:
 * curl -X GET \
 *   "https://example.com/api/counties?id=1" \
 *   -H "Accept: application/json"
 */

/**
 * @api {post} /api/counties Create a county
 * @apiName CreateCounty
 * @apiGroup Counties
 * @apiVersion 1.0.0
 *
 * @apiDescription Creates a new county after validating the request.
 *
 * @apiBody {String} name County name. Required; minimum length 1.
 * @apiBody {String} arms URL representing the county's arms.
 * Required; must be a valid HTTP or HTTPS URL.
 *
 * @apiUse CountyObject
 *
 * @apiSuccessExample {json} Success response:
 * HTTP/1.1 200 OK
 * {
 *   "county": {
 *     "id": 1,
 *     "name": "Example County",
 *     "arms": "https://example.com/images/example-county.png"
 *   }
 * }
 *
 * @apiUse CountyValidationError
 *
 * @apiExample {curl} Example request:
 * curl -X POST "https://example.com/api/counties" \
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
 * request. Both name and arms are required for every update.
 *
 * @apiParam {Integer} id The ID of the county to update.
 *
 * @apiBody {String} name County name. Required; minimum length 1.
 * @apiBody {String} arms URL representing the county's arms.
 * Required; must be a valid HTTP or HTTPS URL.
 *
 * @apiUse CountyObject
 *
 * @apiSuccessExample {json} Success response:
 * HTTP/1.1 200 OK
 * {
 *   "county": {
 *     "id": 1,
 *     "name": "Updated County",
 *     "arms": "https://example.com/images/updated-county.png"
 *   }
 * }
 *
 * @apiUse CountyValidationError
 *
 * @apiError (404) NotFound The specified county does not exist.
 *
 * @apiExample {curl} Example request:
 * curl -X PUT "https://example.com/api/counties/1" \
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
 * model in the JSON response.
 *
 * @apiParam {Integer} id The ID of the county to delete.
 *
 * @apiSuccess {Object} county The deleted county resource.
 * @apiSuccess {Integer} county.id The unique county identifier.
 * @apiSuccess {String} county.name The county name.
 * @apiSuccess {String} county.arms The county's arms URL.
 *
 * @apiSuccessExample {json} Success response:
 * HTTP/1.1 200 OK
 * {
 *   "county": {
 *     "id": 1,
 *     "name": "Example County",
 *     "arms": "https://example.com/images/example-county.png"
 *   }
 * }
 *
 * @apiError (404) NotFound The specified county does not exist.
 *
 * @apiExample {curl} Example request:
 * curl -X DELETE "https://example.com/api/counties/1" \
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

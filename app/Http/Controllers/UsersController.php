<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

/**
 * @apiDefine UserValidationError
 *
 * @apiError (422) UnprocessableEntity Request validation failed.
 *
 * @apiErrorExample {json} Validation error response:
 * HTTP/1.1 422 Unprocessable Entity
 * {
 *   "message": "The given data was invalid.",
 *   "errors": {
 *     "email": [
 *       "The email field must be a valid email address."
 *     ],
 *     "password": [
 *       "The password field is required."
 *     ]
 *   }
 * }
 *
 * Note: The actual errors depend on the submitted data,
 * Laravel version, and application validation configuration.
 */

/**
 * @api {post} /api/users/login Log in a user
 * @apiName LoginUser
 * @apiGroup Users
 * @apiVersion 1.0.0
 *
 * @apiDescription Authenticates a user using an email address and
 * password. If authentication succeeds, the endpoint deletes all
 * existing tokens belonging to that user, creates a new Sanctum
 * access token named "access", and returns the user with the new
 * plain-text token assigned to the user model's token attribute.
 *
 * Important: Existing tokens are revoked on successful login.
 * Clients using previously issued tokens may no longer be authenticated.
 *
 * @apiBody {String} email User's email address. Required; must be valid.
 * @apiBody {String} password User's password. Required.
 *
 * @apiSuccess {Object} user The authenticated user object.
 * @apiSuccess {String} user.token Newly issued Sanctum plain-text
 * access token.
 *
 * @apiSuccessExample {json} Success response:
 * HTTP/1.1 200 OK
 * {
 *   "user": {
 *     "id": 1,
 *     "email": "user@example.com",
 *     "token": "1|example-token-value"
 *   }
 * }
 *
 * Note: The user fields shown above are illustrative. The actual
 * serialized fields depend on the User model's configuration.
 * The token attribute is assigned by the controller. This example
 * does not guarantee that sensitive model attributes are hidden.
 *
 * @apiError (401) Unauthorized Invalid email or password.
 *
 * @apiErrorExample {json} Invalid credentials:
 * HTTP/1.1 401 Unauthorized
 * {
 *   "message": "Invalid email or password"
 * }
 *
 * @apiUse UserValidationError
 *
 * @apiExample {curl} Example request:
 * curl -X POST "https://example.com/api/users/login" \
 *   -H "Content-Type: application/json" \
 *   -H "Accept: application/json" \
 *   -d '{
 *     "email": "user@example.com",
 *     "password": "your-password"
 *   }'
 */

/**
 * @api {get} /api/users List users
 * @apiName GetUsers
 * @apiGroup Users
 * @apiVersion 1.0.0
 *
 * @apiDescription Returns all users retrieved by User::all().
 * This endpoint is protected by the auth:sanctum middleware.
 * A valid authenticated request is required.
 *
 * The controller does not apply pagination or filtering.
 *
 * @apiHeader {String} Authorization Bearer access token.
 * @apiHeaderExample {String} Authorization header:
 * Authorization: Bearer 1|example-token-value
 *
 * @apiSuccess {Object[]} users List of user objects.
 * @apiSuccess {Integer} users.id User identifier.
 * @apiSuccess {String} users.email User email address.
 *
 * @apiSuccessExample {json} Success response:
 * HTTP/1.1 200 OK
 * {
 *   "users": [
 *     {
 *       "id": 1,
 *       "email": "user@example.com"
 *     },
 *     {
 *       "id": 2,
 *       "email": "another@example.com"
 *     }
 *   ]
 * }
 *
 * Note: The example shows only the known user fields. The actual
 * serialized attributes depend on the User model configuration.
 * Additional fields may be included, and sensitive fields are not
 * guaranteed to be hidden by the controller alone.
 *
 * @apiError (401) Unauthorized The request is unauthenticated or
 * the supplied access token is invalid.
 *
 * @apiErrorExample {json} Unauthenticated response:
 * HTTP/1.1 401 Unauthorized
 * {
 *   "message": "Unauthenticated."
 * }
 *
 * @apiExample {curl} Example request:
 * curl -X GET "https://example.com/api/users" \
 *   -H "Accept: application/json" \
 *   -H "Authorization: Bearer 1|example-token-value"
 */

class UsersController extends Controller
{
    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $email)->first();
        if (!$user || !Hash::check($password, $password ? $user->password : '')) {
            return response()->json([
                'message' => 'Invalid email or password'
            ], 401);
        }
        $user->tokens()->delete();
        $user->token = $user->createToken('access')->plainTextToken;
        return response()->json([
            'user' => $user,
        ]);
    }

    public function index()
    {
        $users = User::all();
        return response()->json(compact('users'));
    }
}

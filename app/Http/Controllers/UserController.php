<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// create Swegger annotation
use OpenApi\Attributes as OA;


class UserController extends Controller
{

    // add anotation for login method 
    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"User"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="email", type="string", example="wendikardian@gmail.com"
     *                ),
     *                @OA\Property(property="password", type="string", example="password")
     *            )
     *       )
     *    ),
     *   @OA\Response(
     *      response=200,
     *     description="success",
     *    @OA\JsonContent(
     *       @OA\Property(property="data", type="object",
     *         @OA\Property(property="token", type="string", example="token")
     *       )
     *     )
     *   )
     * )
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid email or password'
            ], 401);
        }
        $token = $user->createToken('token-name')->plainTextToken;
        return response()->json([
            "data" => [
                "token" => $token
            ]
        ]);
    }

    // create method for register 
    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"User"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="email", type="string", example="wendikardian@gmail.com"),
     *                 @OA\Property(property="name", type="string", example="Wendi Kardian"),
     *                 @OA\Property(property="password", type="string", example="password")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="User created"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="email", type="string", example="wendikardian@gmail.com"),
     *                 @OA\Property(property="name", type="string", example="Wendi Kardian"),
     *                 @OA\Property(property="password", type="string", example="password")
     *             )
     *         )
     *     )
     * )
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'name' => 'required|string|max:50',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'email' => $request->email,
            'name' => $request->name,
            'password' => Hash::make($request->password)
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'User created',
            'data' => $user
        ], 201);
    }

    // create method for logout
    /**
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"User"},
     *    security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Token deleted")
     *         )
     *     )
     * )
     */

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Token deleted'
        ]);
    }
}

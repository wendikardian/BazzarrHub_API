<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;

class BrandController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/brands",
     *     tags={"Brand"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="keyword",
     *         in="query",
     *         description="Keyword for search brand",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="brands", type="array", @OA\Items(ref="#/components/schemas/Brand")),
     *         )
     *     )
     * )
     */


    public function index(Request $request)
    {
        $query = $request->input('keyword');
        if ($query) {
            $brands = Brand::where('name', 'like', "%$query%")
                ->orWhere('description', 'like', "%$query%")
                ->orWhere('logo', 'like', "%$query%")
                ->orWhere('website', 'like', "%$query%")
                ->orWhere('email', 'like', "%$query%")
                ->orderBy('name', 'asc')
                ->paginate($perPage = 10, $columns = ['*'], $pageName = 'page');
        } else {
            $brands = Brand::orderBy('name', 'asc')
                ->paginate($perPage = 10, $columns = ['*'], $pageName = 'page');
        }

        return response()->json([
            'status' => 'success',
            'brands' => $brands
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/brands",
     *     tags={"Brand"},
     *    security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "description"},
     *             @OA\Property(property="name", type="string", example="Brand 1"),
     *             @OA\Property(property="description", type="string", example="Brand description"),
     *             @OA\Property(property="logo", type="string", example="brand_logo.png"),
     *             @OA\Property(property="website", type="string", example="https://www.brand1.com"),
     *             @OA\Property(property="email", type="string", example="info@brand1.com"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Brand created"),
     *             @OA\Property(property="brand", ref="#/components/schemas/Brand"),
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        // column : name, description
        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'required|string|max:255',
            'logo' => 'string',
            'website' => 'string',
            'email' => 'string'
        ], [
            'name.required' => 'The name field is required.',
            'description.required' => 'The description field is required.',
            'name.string' => 'The name field must be a string.',
            'description.string' => 'The description field must be a string.',
            'name.max' => 'The name field must not exceed 50 characters.',
            'description.max' => 'The description field must not exceed 255 characters.',
            'logo.string' => 'The logo field must be a string.',
            'website.string' => 'The website field must be a string.',
        ]);

        $brand = Brand::create($validatedData);
        return response()->json([
            'status' => 'success',
            'message' => 'Brand created',
            'brand' => $brand
        ]);
    }
    /**
     * @OA\Get(
     *    path="/api/brands/{id}",
     *    tags={"Brand"},
     *    security={{"bearerAuth":{}}},
     *    @OA\Parameter(
     *        name="id",
     *        in="path",
     *        description="ID of brand",
     *        required=true,
     *        @OA\Schema(
     *            type="integer"
     *        )
     *    ),
     *    @OA\Response(
     *        response=200,
     *        description="success",
     *        @OA\JsonContent(
     *            @OA\Property(property="status", type="string", example="success"),
     *            @OA\Property(property="brand", ref="#/components/schemas/Brand"),
     *        )
     *    )
     * )
     */

    public function show($id)
    {
        $brand = Brand::find($id);
        if (!$brand) {
            return response()->json([
                'status' => 'error',
                'message' => 'Brand not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'brand' => $brand
        ]);
    }
    // create anotation for update method

    /**
     * Update the specified resource in storage.
     */

    // add anotation for update method
    /**
     * @OA\Put(
     *     path="/api/brands/{id}",
     *     tags={"Brand"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of brand",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "description"},
     *             @OA\Property(property="name", type="string", example="Brand 1"),
     *             @OA\Property(property="description", type="string", example="Brand description"),
     *             @OA\Property(property="logo", type="string"),
     *             @OA\Property(property="website", type="string"),
     *             @OA\Property(property="email", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Brand updated"),
     *             @OA\Property(property="brand", ref="#/components/schemas/Brand"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Brand not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Brand not found"),
     *         )
     *     )
     * )
     */

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'required|string|max:255',
            'logo' => 'string',
            'website' => 'string',
            'email' => 'string'
        ], [
            'name.required' => 'The name field is required.',
            'description.required' => 'The description field is required.',
            'name.string' => 'The name field must be a string.',
            'description.string' => 'The description field must be a string.',
            'name.max' => 'The name field must not exceed 50 characters.',
            'description.max' => 'The description field must not exceed 255 characters.',
            'logo.string' => 'The logo field must be a string.',
            'website.string' => 'The website field must be a string.',
        ]);
        $brand = Brand::find($id);
        if (!$brand) {
            return response()->json([
                'status' => 'error',
                'message' => 'Brand not found'
            ], 404);
        } else {
            $brand->update($validatedData);
            return response()->json([
                'status' => 'success',
                'message' => 'Brand updated',
                'brand' => $brand
            ]);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/brands/{id}",
     *     tags={"Brand"},
     *     security={{"bearerAuth
     *    ":{}}},
     *    @OA\Parameter(
     *       name="id",
     *      in="path",
     *     description="ID of brand",
     *   required=true,
     * @OA\Schema(
     *  type="integer"
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="success",
     * @OA\JsonContent(
     * @OA\Property(property="status", type="string", example="success"),
     * @OA\Property(property="message", type="string", example="Brand deleted"),
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="Brand not found",
     * @OA\JsonContent(
     * @OA\Property(property="status", type="string", example="error"),
     * @OA\Property(property="message", type="string", example="Brand not found"),
     * )
     * )
     * )
     */
    public function destroy(string $id)
    {
        $brand = Brand::find($id);
        if (!$brand) {
            return response()->json([
                'status' => 'error',
                'message' => 'Brand not found'
            ], 404);
        } else {
            $brand->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Brand deleted'
            ]);
        }
    }
}

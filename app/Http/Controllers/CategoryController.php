<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    //  add anotation for index method
    /**
     * @OA\Get(
     *     path="/api/categories",
     *     tags={"Category"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="keyword",
     *         in="query",
     *         description="Keyword for search category",
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
     *             @OA\Property(property="categories", type="array", @OA\Items(ref="#/components/schemas/Category")),
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $query = $request->input('keyword');
        if ($query) {
            $categories = Category::where('name', 'like', "%$query%")
                ->orderBy('name', 'asc')
                ->paginate($perPage = 10, $columns = ['*'], $pageName = 'page');
        } else {
            $categories = Category::orderBy('name', 'asc')
                ->paginate($perPage = 10, $columns = ['*'], $pageName = 'page');
        }

        return response()->json([
            'status' => 'success',
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */

    //  add anotation for store method
    /**
     * @OA\Post(
     *     path="/api/categories",
     *     tags={"Category"},
     *    security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "description"},
     *             @OA\Property(property="name", type="string", example="Category 1"),
     *             @OA\Property(property="description", type="string", example="Category description"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Category created"),
     *             @OA\Property(property="category", ref="#/components/schemas/Category"),
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        // column : name, description
        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'required|string|max:255'
        ], [
            'name.required' => 'The name field is required.',
            'description.required' => 'The description field is required.',
            'name.string' => 'The name field must be a string.',
            'description.string' => 'The description field must be a string.',
            'name.max' => 'The name field must not exceed 50 characters.',
            'description.max' => 'The description field must not exceed 255 characters.'
        ]);

        $category = Category::create($validatedData);
        return response()->json([
            'status' => 'success',
            'message' => 'Category created',
            'category' => $category
        ]);
    }

    /**
     * Display the specified resource.
     */


    // add anotation for show method
    /**
     * @OA\Get(
     *    path="/api/categories/{id}",
     *   tags={"Category"},
     *  security={{"bearerAuth":{}}},
     * security={{"bearerAuth":{}}},
     *    @OA\Parameter(
     *        name="id",
     *       in="path",
     *     description="ID of category",
     *   required=true,
     * @OA\Schema(
     *    type="integer"
     * )
     * ),
     * @OA\Response(
     *   response=200,
     * description="success",
     * @OA\JsonContent(
     *    @OA\Property(property="status", type="string", example="success"),
     *  @OA\Property(property="category", ref="#/components/schemas/Category"),
     * )
     * )
     * )
     */
    public function show(string $id)
    {

        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found'
            ], 404);
        } else {
            return response()->json([
                'status' => 'success',
                'category' => $category
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */

    // add anotation for update method
    /**
     * @OA\Put(
     *     path="/api/categories/{id}",
     *     tags={"Category"},
     *     security={{"bearerAuth
     *    ":{}}},
     *    @OA\Parameter(
     *       name="id",
     *     in="path",
     *   description="ID of category",
     * required=true,
     * @OA\Schema(
     *   type="integer"
     * )
     * ),
     * @OA\RequestBody(
     *   required=true,
     * @OA\JsonContent(
     *  required={"name", "description"},
     * 
     * @OA\Property(property="name", type="string", example="Category 1"),
     * @OA\Property(property="description", type="string", example="Category description"),
     * )
     * ),
     * @OA\Response(
     *  response=200,
     * description="success",
     * @OA\JsonContent(
     * @OA\Property(property="status", type="string", example="success"),
     * @OA\Property(property="message", type="string", example="Category updated"),
     * @OA\Property(property="category", ref="#/components/schemas/Category"),
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="Category not found",
     * @OA\JsonContent(
     *  
     * @OA\Property(property="status", type="string", example="error"),
     * @OA\Property(property="message", type="string", example="Category not found"),
     * )
     * )
     * )
     */
    public function update(Request $request, string $id)
    {
        //
        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'required|string|max:255'
        ], [
            'name.required' => 'The name field is required.',
            'description.required' => 'The description field is required.',
            'name.string' => 'The name field must be a string.',
            'description.string' => 'The description field must be a string.',
            'name.max' => 'The name field must not exceed 50 characters.',
            'description.max' => 'The description field must not exceed 255 characters.'
        ]);
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found'
            ], 404);
        } else {
            $category->update($validatedData);
            return response()->json([
                'status' => 'success',
                'message' => 'Category updated',
                'category' => $category
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */

    // add anotation for destroy method
    /**
     * @OA\Delete(
     *     path="/api/categories/{id}",
     *     tags={"Category"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of category",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Category deleted"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Category not found"),
     *         )
     *     )
     * )
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found'
            ], 404);
        } else {
            $category->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Category deleted'
            ]);
        }
    }
}

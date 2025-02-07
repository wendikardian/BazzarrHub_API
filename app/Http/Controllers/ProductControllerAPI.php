<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use OpenApi\Attributes as OA;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Dokumentasi API",
 *      description="Gunakan API ini untuk mengelola data produk",
 *      @OA\Contact(
 *          email="wendikardian@gmail.com"
 *      )
 * )
 * 
 * @OA\SecurityScheme(
 *    type="http",
 *    scheme="bearer",
 *    securityScheme="bearerAuth"
 * )
 * 
 * 
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="ProductsAPI"
 * )
 * 
 * 
 * @OA\Schema(
 *     schema="Product",
 *     required={"id", "name", "price", "stock"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Product 1"),
 *     @OA\Property(property="price", type="number", example=10000),
 *     @OA\Property(property="stock", type="integer", example=10),
 *      @OA\Property(property="category_id", type="integer", example=1),
 *    @OA\Property(property="brand_id", type="integer", example=1),
 * )
 * 
 * @OA\Schema(
 *     schema="Category",
 *     required={"id", "name", "description"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Category 1"),
 *     @OA\Property(property="description", type="string", example="Category description"),
 * )
 * @OA\Schema(
 *    schema="Brand",
 *   required={"id", "name", "description", "logo", "website", "email"},
 * @OA\Property(property="id", type="integer", example=1),
 * @OA\Property(property="name", type="string", example="Brand 1"),
 * @OA\Property(property="description", type="string", example="Brand description"),
 * @OA\Property(property="logo", type="string", example="logo.jpg"),
 * @OA\Property(property="website", type="string", example="https://www.brand1.com"),
 * @OA\Property(property="email", type="string", example="email@gmail.com"),
 * )
 * 
 * @OA\Schema(
 *     schema="User",
 *     required={"name", "email", "password"},
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", example="john@example.com"),
 *     @OA\Property(property="password", type="string", example="password123"),
 * )
 */



class ProductControllerAPI extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @OA\Get(
     *     path="/api/products",
     *     tags={"Product"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="products", type="array", @OA\Items(ref="#/components/schemas/Product")),
     *          )
     *      )
     * )
     */
    public function index(Request $request)
    {
        $query = $request->input('keyword');

        if ($query) {
            $products = Product::with(['category', 'brand'])
            ->where('name', 'like', "%$query%")
            ->orWhere('price', 'like', "%$query%")
            ->orWhere('stock', 'like', "%$query%")
            ->orderBy('price', 'desc');
        } else {
            $products = Product::with(['category', 'brand'])
            ->orderBy('price', 'desc');
        }

        $category = $request->input('category');
        if ($category) {
            $products->whereHas('category', function ($query) use ($category) {
            $query->where('name', 'like', "%$category%");
            });
        }

        $brand = $request->input('brand');
        if ($brand) {
            $products->whereHas('brand', function ($query) use ($brand) {
            $query->where('name', 'like', "%$brand%");
            });
        }

        $products = $products->paginate(10);

        $products->getCollection()->transform(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'stock' => $product->stock,
                'category' => $product->category ? $product->category->name : null,
                'brand' => $product->brand ? $product->brand->name : null,
            ];
        });

        return response()->json([
            'status' => 'success',
            'products' => $products
        ]);
    }



    /**
     * Store a newly created resource in storage.
     */
    // add some documentation using Swegger

    /**
     * @OA\Post(
     *     path="/api/products",
     *     tags={"Product"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name", "price", "stock"},
     *              @OA\Property(property="name", type="string", example="Product 1"),
     *              @OA\Property(property="price", type="number", example=10000),
     *              @OA\Property(property="stock", type="integer", example=10),
     *              @OA\Property(property="category_id", type="integer", example=1),
     *            @OA\Property(property="brand_id", type="integer", example=1),
     *          )
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Product created"),
     *              @OA\Property(property="product", ref="#/components/schemas/Product"),
     *          )
     *      )
     * )
     */

    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|integer',
            'brand_id' => 'required|integer'
        ], [
            'name.required' => 'The name field is required.',
            'price.required' => 'The price field is required.',
            'stock.required' => 'The stock field is required.',
            'name.string' => 'The name field must be a string.',
            'price.numeric' => 'The price field must be a number.',
            'stock.integer' => 'The stock field must be an integer.',
            'category_id.required' => 'The category_id field is required.',
            'brand_id.required' => 'The brand_id field is required.',
            'category_id.integer' => 'The category_id field must be an integer.',
            'brand_id.integer' => 'The brand_id field must be an integer.'
        ]);

        $product = Product::create($validatedData);
        return response()->json([
            'status' => 'success',
            'message' => 'Product created',
            'product' => $product
        ]);
    }

    /**
     * Display the specified resource.
     */

    /**
     * @OA\Get(
     *    path="/api/products/{id}",
     *   tags={"Product"},
     * security={{"bearerAuth":{}}},
     *  @OA\Parameter(
     *     name="id",
     *    in="path",
     *  required=true,
     * description="ID of the product",
     * @OA\Schema(
     *   type="string"
     * )
     * ),
     * @OA\Response(
     *   response=200,
     * description="success",
     * @OA\JsonContent(
     *  @OA\Property(property="status", type="string", example="success"),
     * @OA\Property(property="product", ref="#/components/schemas/Product"),
     * )
     * ),
     * @OA\Response(
     *  response=404,
     * description="Product not found",
     * @OA\JsonContent(
     * @OA\Property(property="status", type="string", example="error"),
     * @OA\Property(property="message", type="string", example="Product not found"),
     * )
     * )
     * )
     */

    public function show(string $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        } else {
            return response()->json([
                'status' => 'success',
                'product' => $product
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */

    /**
     * @OA\Put(
     *    path="/api/products/{id}",
     *  tags={"Product"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     *  name="id",
     * in="path",
     * required=true,
     * description="ID of the product",
     * @OA\Schema(
     * type="string"
     * )
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"name", "price", "stock"},
     * @OA\Property(property="name", type="string", example="Product 1"),
     * @OA\Property(property="price", type="number", example=10000),
     * @OA\Property(property="stock", type="integer", example=10),
     * @OA\Property(property="category_id", type="integer", example=1),
     * @OA\Property(property="brand_id", type="integer", example=1),
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="success",
     * @OA\JsonContent(
     * @OA\Property(property="status", type="string", example="success"),
     * @OA\Property(property="message", type="string", example="Product updated"),
     * @OA\Property(property="product", ref="#/components/schemas/Product"),
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="Product not found",
     * @OA\JsonContent(
     * @OA\Property(property="status", type="string", example="error"),
     * @OA\Property(property="message", type="string", example="Product not found"),
     * )
     * )
     * )
     */

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|integer',
            'brand_id' => 'required|integer'
        ], [
            'name.required' => 'The name field is required.',
            'price.required' => 'The price field is required.',
            'stock.required' => 'The stock field is required.',
            'name.string' => 'The name field must be a string.',
            'price.numeric' => 'The price field must be a number.',
            'stock.integer' => 'The stock field must be an integer.',
            'category_id.required' => 'The category_id field is required.',
            'brand_id.required' => 'The brand_id field is required.',
            'category_id.integer' => 'The category_id field must be an integer.',
            'brand_id.integer' => 'The brand_id field must be an integer.'
        ]);
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        } else {
            $product->update($validatedData);
            return response()->json([
                'status' => 'success',
                'message' => 'Product updated',
                'product' => $product
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */

    /**
     * @OA\Delete(
     *   path="/api/products/{id}",
     * tags={"Product"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the product",
     * @OA\Schema(
     * type="string"
     * )
     *  
     * 
     * ),
     * @OA\Response(
     * response=200,
     * description="success",
     * @OA\JsonContent(
     * @OA\Property(property="status", type="string", example="success"),
     * @OA\Property(property="message", type="string", example="Product deleted"),
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="Product not found",
     * @OA\JsonContent(
     * @OA\Property(property="status", type="string", example="error"),
     * @OA\Property(property="message", type="string", example="Product not found"),
     * )
     * )
     * )
     */

    public function destroy(string $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        } else {
            $product->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Product deleted'
            ]);
        }
    }
}

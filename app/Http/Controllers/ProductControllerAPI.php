<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use OpenApi\Attributes as OA;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Dokumentasi API",
 *      description="Lorem Ipsum",
 *      @OA\Contact(
 *          email="wendikardian@gmail.com"
 *      )
 *     ),
 * 
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *    description="ProductsAPI"
 * )
 * 
 * @OA\Schema(
 *     schema="Product",
 *     required={"id", "name", "price", "stock"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Product 1"),
 *     @OA\Property(property="price", type="number", example=10000),
 *     @OA\Property(property="stock", type="integer", example=10),
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
    public function index()
    {
        $products = Product::all();
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
            'name' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer'
        ], [
            'name.required' => 'The name field is required.',
            'price.required' => 'The price field is required.',
            'stock.required' => 'The stock field is required.'
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
            'stock' => 'required|integer'
        ], [
            'name.required' => 'The name field is required.',
            'price.required' => 'The price field is required.',
            'stock.required' => 'The stock field is required.'
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

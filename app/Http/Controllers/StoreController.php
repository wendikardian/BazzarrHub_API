<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;



class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->query('query');
        //
        // $store = Store::all(); 
        // $store = Store::paginate(5);
        $store = Store::where('name', 'like', "%$query%")
        ->orWhere('phone_number', 'like', "%$query%")
        ->orWhere('address', 'like', "%$query%")
        ->paginate($perPage = 5, $columns = ['*'], $pageName = 'page', );
        return response()->json([
            'status' => 'success',
            'store' => $store
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validatedData = $request->validate(
            [
                'name' => 'required|string|max:255',
                'phone_number' => 'required|string',
                'address' => 'required|string'
            ],
            [
                'name.required' => 'The name field is required.',
                'phone_number.required' => 'The phone number field is required.',
                'address.required' => 'The address field is required.',
                'name.string' => 'The name field must be a string.',
                'phone_number.numeric' => 'The phone number field must be an integer.',
                'address.string' => 'The address field must be a string.',
            ]
        );
        $store = Store::create($validatedData);
        // check if the store is created successfully or not
        if ($store) {
            return response()->json([
                'status' => 'success',
                'message' => 'Store created',
                'store' => $store
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Store failed to create'
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $store = Store::find($id);
        if (!$store) {
            return response()->json([
                'status' => 'error',
                'message' => 'Store not found'
            ], 404);
        } else {
            return response()->json([
                'status' => 'success',
                'store' => $store
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $validatedData = $request->validate([
            'name' => 'required',
            'phone_number' => 'required',
            'address' => 'required'
        ]);
        $store = Store::find($id);
        if (!$store) {
            return response()->json([
                'status' => 'error',
                'message' => 'Store not found'
            ], 404);
        } else {
            $store->update($validatedData);
            return response()->json([
                'status' => 'success',
                'message' => 'Store updated',
                'store' => $store
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $store = Store::find($id);
        if (!$store) {
            return response()->json([
                'status' => 'error',
                'message' => 'Store not found'
            ], 404);
        } else {
            $store->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Store deleted'
            ]);
        }
    }
}

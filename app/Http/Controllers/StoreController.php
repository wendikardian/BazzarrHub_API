<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $store = Store::all();
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
        $validatedData = $request->validate([
            'name' => 'required',
            'phone_number' => 'required',
            'address' => 'required'
        ]);
        $store = Store::create($validatedData);
        // check if the store is created successfully or not
        if ($store) {
            return response()->json([
                'status' => 'success',
                'message' => 'Store created',
                'store' => $store
            ]);
        }else {
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
        }else {
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
        if(!$store) {
            return response()->json([
                'status' => 'error',
                'message' => 'Store not found'
            ], 404);
        }else {
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
        if(!$store) {
            return response()->json([
                'status' => 'error',
                'message' => 'Store not found'
            ], 404);
        }else {
            $store->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Store deleted'
            ]);
        }
        
    }

}

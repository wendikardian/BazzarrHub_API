<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
class ProductController extends Controller
{
    public function index()
    {
        // get all data to show in the index page
        $products = Product::all();
        return view('products.index')->with('products', $products);

    }

    public function create()
    {
        // show the form to add new product
        return view('products.create');
    }

    public function store(Request $request)
    {
        // save the data from the form
        $validatedData = $request->validate([
            'name' => 'required',
            'price' => 'required',
            'stock' => 'required'
        ]);
        $product = Product::create($validatedData);
        return redirect()->route('products.index');
    }


    public function show($id)
    {
        // show the detail of the product based on the id
        $product = Product::find($id);
        return view('products.show')->with('product', $product);
    }


    public function edit($id)
    {
        // show the form to edit the product
        $product = Product::find($id);
        return view('products.edit')->with('product', $product);
    }


    public function update(Request $request, $id)
    {
        // update the product based on the id
        $validatedData = $request->validate([
            'name' => 'required',
            'price' => 'required',
            'stock' => 'required'
        ]);
        Product::whereId($id)->update($validatedData);
        return redirect()->route('products.index');
    }

    public function destroy($id)
    {
        // delete the product based on the id
        Product::destroy($id);
        return redirect()->route('products.index');
    }
    
    
}

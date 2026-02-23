<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();
        return view('admin.components.product.index', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:products,name',
            'sell_price' => 'required|numeric|min:0'
        ]);

        Product::create([
            'name' => $request->name,
            'sell_price' => $request->sell_price,
        ]);

        return back()->with('success', 'Product Created Successfully');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:products,name,' . $product->id,
            'sell_price' => 'required|numeric|min:0'
        ]);

        $product->update([
            'name' => $request->name,
            'sell_price' => $request->sell_price,
        ]);

        return back()->with('success', 'Product Updated Successfully');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Product Deleted Successfully');
    }
}

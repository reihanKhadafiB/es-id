<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        return Inertia::render('Products/Index', [
            'products' => Product::with('category')->get(),
            'categories' => Category::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        Product::create($validated);

        return back()->with('success', 'Produk ditambahkan.');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $product->update($validated);

        return back()->with('success', 'Produk diupdate.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return back()->with('success', 'Produk dihapus.');
    }
}

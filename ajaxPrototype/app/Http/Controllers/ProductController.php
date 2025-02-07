<?php

namespace App\Http\Controllers;

use App\Repositories\ProductRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }
    //
    public function index()
    {
        $products = Product::all();
        return view('welcome', compact('products'));
    }

    public function public()
    {
        $products = Product::all();
        return view('public', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'price' => 'required|integer',
            'stock' => 'required|integer',
        ]);

        $product = Product::create([
            'title' => $request->title,
            'content' => $request->content,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        return response()->json($product);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
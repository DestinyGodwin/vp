<?php

namespace App\Http\Controllers\v1;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\products\StoreProductRequest;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $universityId = $request->query('university_id');

        $products = Product::whereHas('store', function ($query) use ($universityId) {
            $query->where('university_id', $universityId);
        })
        ->where('status', 'active')
        ->with('store.university', 'images')
        ->paginate(10);

        return response()->json($products);
    }

    public function store(StoreProductRequest $request)
    {
        $user = auth()->user();
        $store = $user->store; 

        if (!$store) {
            return response()->json(['message' => 'No store found for user.'], 403);
        }

        $product = $store->products()->create($request->only(['name', 'description', 'price', 'category_id']));

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');

                $product->images()->create([
                    'image_path' => $path,
                ]);
            }
        }

        return response()->json(['message' => 'Product created.', 'product' => $product->load('images')]);
    }

    public function show(Product $product)
    {
        return response()->json($product->load('store.university', 'images'));
    }

    public function update(StoreProductRequest $request, Product $product)
    {
        $user = auth()->user();

        if ($product->store->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $product->update($request->only(['name', 'description', 'price', 'category_id']));

        if ($request->hasFile('images')) {
            $product->images()->delete(); // Remove old images

            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');

                $product->images()->create([
                    'image_path' => $path,
                ]);
            }
        }

        return response()->json(['message' => 'Product updated.', 'product' => $product->load('images')]);
    }

    public function destroy(Product $product)
    {
        $user = auth()->user();

        if ($product->store->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted.']);
    }
}
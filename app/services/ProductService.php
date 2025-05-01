<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class ProductService
{
    public function getAll($user): Collection
    {
        $query = Product::with(['store.university', 'store.user', 'images', 'category'])
            ->where('status', 'active')
            ->whereHas('store', function ($query) {
                $query->where('type', '!=', 'food')->where('type', '!=', 'drink');
            });

        if ($user && $user->university_id) {
            $query->whereHas('store', function ($q) use ($user) {
                $q->where('university_id', $user->university_id);
            });
        }

        return $query->latest()->get();
    }

    public function findById(string $id): Product
    {
        return Product::with(['store.university', 'store.user', 'images', 'category'])->findOrFail($id);
    }

    public function create(Request $request): Product
    {
        $user = Auth::user();
        $store = $user->store;

        if (!$store) {
            abort(403, 'No store found for user.');
        }

        $product = $store->products()->create($request->only(['name', 'description', 'price', 'category_id']));

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $product->images()->create(['image_path' => $path]);
            }
        }

        return $product->load('images');
    }

    public function update(string $id, Request $request): Product
    {
        $user = Auth::user();
        $product = Product::findOrFail($id);

        if ($product->store->user_id !== $user->id) {
            abort(403, 'Unauthorized.');
        }

        $product->update($request->only(['name', 'description', 'price', 'category_id']));

        if ($request->hasFile('images')) {
            $product->images()->delete();
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $product->images()->create(['image_path' => $path]);
            }
        }

        return $product->load('images');
    }

    public function delete(string $id): void
    {
        $user = Auth::user();
        $product = Product::findOrFail($id);

        if ($product->store->user_id !== $user->id) {
            abort(403, 'Unauthorized.');
        }

        $product->delete();
    }
}

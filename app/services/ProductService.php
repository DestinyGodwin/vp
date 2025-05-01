<?php
namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;

class ProductService
{
    public function getFilteredProducts()
    {
        $user = Auth::user();

        $excludedCategories = Category::whereIn('name', ['food', 'drinks'])->pluck('id');

        $query = Product::with(['store.university', 'store.user', 'images', 'category'])
            ->whereNotIn('category_id', $excludedCategories)
            ->where('status', 'active');

        if ($user && $user->university_id) {
            $query->whereHas('store', fn($q) => $q->where('university_id', $user->university_id));
        }

        $products = $query->paginate(10);

        if ($user && $user->university_id && $products->isEmpty()) {
            return response()->json([
                'message' => 'No products from your university yet.'
            ]);
        }

        return $products;
    }

    public function findById(string $id): ?Product
    {
        return Product::with(['store.university', 'store.user', 'images', 'category'])->findOrFail($id);
    }

    public function getByCategoryName(string $name)
    {
        $category = Category::where('name', $name)->firstOrFail();

        return Product::with(['store.university', 'store.user', 'images', 'category'])
            ->where('category_id', $category->id)
            ->where('status', 'active')
            ->paginate(10);
    }

    public function getByStore(string $storeId)
    {
        return Product::with(['store.university', 'store.user', 'images', 'category'])
            ->where('store_id', $storeId)
            ->where('status', 'active')
            ->paginate(10);
    }

    public function getByUniversity(string $universityId)
    {
        return Product::with(['store.university', 'store.user', 'images', 'category'])
            ->whereHas('store', fn($q) => $q->where('university_id', $universityId))
            ->where('status', 'active')
            ->paginate(10);
    }

    public function getByCountry(string $country)
    {
        return Product::with(['store.university', 'store.user', 'images', 'category'])
            ->whereHas('store.university', fn($q) => $q->where('country', $country))
            ->where('status', 'active')
            ->paginate(10);
    }
}

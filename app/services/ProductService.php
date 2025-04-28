<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductService
{
    public function getHomeProducts(array $filters, bool $onlyFoods = false)
    {
        $query = Product::with(['store', 'category', 'images']);

        if (Auth::check()) {
            $user = Auth::user();
            $query->whereHas('store', function ($q) use ($user) {
                $q->where('university_id', $user->university_id);
            });
        }

        if ($onlyFoods) {
            $query->whereHas('category', function ($q) {
                $q->whereIn('name', ['Food', 'Drinks']); // <-- You can also use category IDs if you prefer
            });
        }

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        // Sorting
        if (!empty($filters['sort'])) {
            switch ($filters['sort']) {
                case 'price_low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('price', 'desc');
                    break;
                case 'latest':
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        return $query->paginate(20);
    }
}

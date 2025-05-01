<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductService
{
   
    public function findById(string $id): ?Product
    {
        return Product::with(['store.university', 'store.user', 'images', 'category'])->findOrFail($id);
    }
}

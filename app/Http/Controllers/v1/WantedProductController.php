<?php

namespace App\Http\Controllers\v1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\WantedProduct;
use App\Models\WantedProductImage;
use App\Http\Controllers\Controller;
use App\Notifications\v1\NewWantedProductNotification;
use App\Http\Requests\v1\products\WantedProductRequest;

class WantedProductController extends Controller
{
    public function index()
    {
        return response()->json(WantedProduct::with(['category', 'images'])->latest()->get());
    }

    public function store(WantedProductRequest $request)
    {
        $wantedProduct = WantedProduct::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
        ]);

        if ($request->has('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('wanted_product_images', 'public');
                WantedProductImage::create([
                    'wanted_product_id' => $wantedProduct->id,
                    'image_path' => $path,
                ]);
            }
        }

        // Notify Sellers
        $sellers = User::whereHas('stores.products', function($query) use ($wantedProduct) {
            $query->where('category_id', $wantedProduct->category_id);
        })->where('id', '!=', auth()->id())->distinct()->get();

        foreach ($sellers as $seller) {
            $seller->notify(new NewWantedProductNotification($wantedProduct));
        }

        return response()->json([
            'message' => 'Wanted product posted successfully.',
            'wanted_product' => $wantedProduct->load('images')
        ]);
    }
}
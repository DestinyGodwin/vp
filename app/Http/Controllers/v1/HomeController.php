<?php

namespace App\Http\Controllers\v1;
use App\Http\Controllers\Controller;

use App\Services\ProductService;
use App\Http\Resources\v1\HomeProductResource;
use App\Http\Requests\v1\products\HomeProductRequest;

class HomeController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    // Normal Products
    public function index(HomeProductRequest $request)
    {
        $products = $this->productService->getHomeProducts($request->validated(), false);

        return HomeProductResource::collection($products);
    }

    // Food and Drink Products
    public function foods(HomeProductRequest $request)
    {
        $products = $this->productService->getHomeProducts($request->validated(), true);

        return HomeProductResource::collection($products);
    }
}

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

    public function index(HomeProductRequest $request)
    {
        $products = $this->productService->getHomeProducts($request->validated());

        return HomeProductResource::collection($products);
    }
}

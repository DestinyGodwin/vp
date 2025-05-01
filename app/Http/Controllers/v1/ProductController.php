<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\products\StoreProductRequest;
use App\Http\Resources\v1\ProductResource;
use App\Services\ProductService;

class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $products = $this->productService->getAll($user);

        if ($user && $products->isEmpty()) {
            return response()->json([
                'message' => 'No products available from your university yet.',
                'products' => [],
            ], 200);
        }

        return ProductResource::collection($products);
    }

    public function store(StoreProductRequest $request)
    {
        $product = $this->productService->create($request);
        return response()->json([
            'message' => 'Product created.',
            'product' => new ProductResource($product),
        ], 201);
    }

    public function show(string $id)
    {
        $product = $this->productService->findById($id);
        return new ProductResource($product);
    }

    public function update(StoreProductRequest $request, string $id)
    {
        $product = $this->productService->update($id, $request);
        return response()->json([
            'message' => 'Product updated.',
            'product' => new ProductResource($product),
        ]);
    }

    public function destroy(string $id)
    {
        $this->productService->delete($id);
        return response()->json(['message' => 'Product deleted.']);
    }
    public function productsByCategory(string $name)
{
    $products = $this->productService->getByCategoryName($name);
    return ProductResource::collection($products);
}

public function productsByStore(string $storeId)
{
    $products = $this->productService->getByStore($storeId);
    return ProductResource::collection($products);
}

public function productsByUniversity(string $universityId)
{
    $products = $this->productService->getByUniversity($universityId);
    return ProductResource::collection($products);
}

public function productsByCountry(string $country)
{
    $products = $this->productService->getByCountry($country);
    return ProductResource::collection($products);
}

}

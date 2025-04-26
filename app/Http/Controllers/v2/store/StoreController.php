<?php

namespace App\Http\Controllers\v2\store;

use App\Models\Store;
use Illuminate\Http\Request;
use App\Services\GeocodingService;
use App\Http\Controllers\Controller;
use App\Http\Requests\v2\store\CreateStoreRequest;

class StoreController extends Controller
{
    protected $geocodingService;

    public function __construct(GeocodingService $geocodingService)
    {
        $this->geocodingService = $geocodingService;
    }
    public function index()
    {
        return Store::where('user_id', auth()->id())->get();
    }

    public function store(CreateStoreRequest $request)
    {
        $geo = $this->geocodingService->geocodeAddress($request->address);

        $store = auth()->user()->stores()->create([
            ...$request->validated(),
            'latitude' => $geo['lat'],
            'longitude' => $geo['lng'],
            'next_payment_due' => now()->addDays(30),
        ]);

        return response()->json(['message' => 'Store created.', 'store' => $store]);
    }

    public function show(Store $store)
    {
        $this->authorize('view', $store);
        return $store;
    }

    public function update(CreateStoreRequest $request, Store $store)
    {
        $this->authorize('update', $store);

        $geo = $this->geocodingService->geocodeAddress($request->address);

        $store->update([
            ...$request->validated(),
            'latitude' => $geo['lat'],
            'longitude' => $geo['lng'],
        ]);

        return response()->json(['message' => 'Store updated.', 'store' => $store]);
    }

    public function destroy(Store $store)
    {
        $this->authorize('delete', $store);
        $store->delete();
        return response()->json(['message' => 'Store deleted.']);
    }
}

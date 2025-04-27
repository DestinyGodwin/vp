<?php

namespace App\Http\Controllers\v1;

use App\Models\Store;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\stores\CreateStoreRequest;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::with('university')->paginate(10);
        return response()->json($stores);
    }

    public function store(CreateStoreRequest $request)
    {
        $store = auth()->user()->store()->create([
            ...$request->validated(),
            'next_payment_due' => now()->addDays(30), // 30 days free
        ]);

        return response()->json(['message' => 'Store created successfully.', 'store' => $store]);
    }

    public function show(Store $store)
    {
        $this->authorize('view', $store);
        return response()->json($store->load('university'));
    }

    public function update(CreateStoreRequest $request, Store $store)
    {
        $this->authorize('update', $store);

        $store->update($request->validated());

        return response()->json(['message' => 'Store updated successfully.', 'store' => $store]);
    }

    public function destroy(Store $store)
    {
        $this->authorize('delete', $store);

        $store->delete();

        return response()->json(['message' => 'Store deleted successfully.']);
    }
}

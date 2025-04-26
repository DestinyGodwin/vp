<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Store extends Model
{
    use HasUuids, HasFactory;


    protected $fillable = [
       'user_id',
        'university_id',
        'name',
        'type',
        'description',
        'status',
        'next_payment_due',
    ];
    protected $casts = [
        'is_active' => 'boolean',
        'next_payment_due' => 'datetime',
    ];
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    // public function foodItems()
    // {
    //     return $this->hasMany(FoodItem::class);
    // }
    // public function subscriptions() {
    //      return $this->hasMany(StoreSubscription::class);
    //      }
    // public function scopeNearby($query, float $lat, float $lng, float $radius = 10)
    // {
    //     return $query->select('*')
    //         ->selectRaw(
    //             '(
    //                      6371 * acos(
    //                          cos(radians(?)) *
    //                          cos(radians(latitude)) *
    //                          cos(radians(longitude) - radians(?)) +
    //                          sin(radians(?)) *
    //                          sin(radians(latitude))
    //                      )
    //                  ) AS distance',
    //             [$lat, $lng, $lat]
    //         )
    //         ->having('distance', '<=', $radius)
    //         ->orderBy('distance');
    // }






    // public function scopeNearby($query, float $lat, float $lng, float $radiusKm = 10)
    // {
    //     return $query->select('*')
    //         ->selectRaw(
    //             "
    //                      ST_Distance_Sphere(point(longitude, latitude), point(?, ?)) / 1000 AS distance_km",
    //             [$lng, $lat]
    //         )
    //         ->having('distance_km', '<=', $radiusKm)
    //         ->orderBy('distance_km');
    // }
    public function university()
    {
        return $this->belongsTo(University::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WantedProductImage extends Model
{
    use HasUuids, HasFactory;
    protected $fillable = [
        'wanted_product_id',
        'image_path',
    ];

    public function wantedProduct()
    {
        return $this->belongsTo(WantedProduct::class);
    }
}

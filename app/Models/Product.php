<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes, HasUuid;

    protected $fillable = [
        'sku',
        'name',
        'description',
        'price',
        'quantity',
        'total',
        'manufacture_date',
        'expiry_date',
        'category_id',
    ];

    protected static function booted()
    {
        static::creating(function ($product) {
            if (empty($product->sku)) {
                $product->sku = 'SKU-' . strtoupper(Str::random(8));
            }
        });
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'order_date',
        'status',
        'total_amount',
    ];

    /**
     * The customer who made the order
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Items in this order
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}

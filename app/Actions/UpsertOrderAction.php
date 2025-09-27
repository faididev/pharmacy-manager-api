<?php

namespace App\Actions;

use App\DTOs\UpsertOrderDto;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class UpsertOrderAction
{
    public function handle(UpsertOrderDto $dto, ?int $id = null): Order
    {
        return DB::transaction(function () use ($dto, $id) {
            // Calculate total amount with proper validation
            $totalAmount = 0;
            foreach ($dto->items as $item) {
                $itemTotal = $item['price'] * $item['quantity'];
                $totalAmount += $itemTotal;
            }

            $order = Order::updateOrCreate(
                ['id' => $id],
                [
                    'customer_id'  => $dto->customerId,
                    'order_date'   => $dto->orderDate?->toDateString() ?? now()->toDateString(),
                    'status'       => $dto->status,
                    'total_amount' => round($totalAmount, 2), // Round to 2 decimal places
                ]
            );

            // clear old items if updating
            if ($id) {
                $order->items()->delete();
            }

            // Create order items
            foreach ($dto->items as $item) {
                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                ]);
            }

            return $order->load(['customer.user', 'items.product']);
        });
    }
}

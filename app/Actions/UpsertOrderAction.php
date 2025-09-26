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
            $order = Order::updateOrCreate(
                ['id' => $id],
                [
                    'customer_id'  => $dto->customerId,
                    'order_date'   => $dto->orderDate?->toDateString(),
                    'status'       => $dto->status,
                    'total_amount' => collect($dto->items)->sum(fn($i) => $i['price'] * $i['quantity']),
                ]
            );

            // clear old items if updating
            if ($id) {
                $order->items()->delete();
            }

            foreach ($dto->items as $item) {
                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                ]);
            }

            return $order->load(['customer', 'items.product']);
        });
    }
}

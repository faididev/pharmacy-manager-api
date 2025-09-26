<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\UpsertOrderDto;
use App\Http\Requests\Api\V1\UpsertOrderRequest;
use App\Http\Requests\Api\V1\UpdateOrderRequest;
use App\Http\Resources\Api\V1\OrderResource;
use App\Models\Order;
use App\Actions\UpsertOrderAction;
use Illuminate\Http\Request;

class OrderController extends ApiController
{
    public function __construct(
        protected UpsertOrderAction $upsertOrderAction,
    ) {}

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $status = $request->get('status');
        $customerId = $request->get('customer_id');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        
        $query = Order::with(['customer', 'items.product']);

        // Filter by status
        if ($status) {
            $query->where('status', $status);
        }

        // Filter by customer
        if ($customerId) {
            $query->where('customer_id', $customerId);
        }

        // Filter by date range
        if ($dateFrom) {
            $query->where('order_date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->where('order_date', '<=', $dateTo);
        }

        $orders = $query->paginate($perPage);
        
        return OrderResource::collection($orders);
    }

    public function show(Order $order)
    {
        $order->load(['customer', 'items.product']);
        return new OrderResource($order);
    }

    public function store(UpsertOrderRequest $request)
    {
        $dto = UpsertOrderDto::fromArray($request->validated());
        $order = $this->upsertOrderAction->handle($dto);
        return new OrderResource($order);
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        $dto = UpsertOrderDto::fromArray($request->validated());
        $order = $this->upsertOrderAction->handle($dto, $order->id);
        return new OrderResource($order);
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return response()->noContent();
    }
}

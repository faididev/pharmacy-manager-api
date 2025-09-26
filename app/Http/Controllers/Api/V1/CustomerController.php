<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\UpsertCustomerDto;
use App\Http\Requests\Api\V1\UpsertCustomerRequest;
use App\Http\Requests\Api\V1\UpdateCustomerRequest;
use App\Http\Resources\Api\V1\CustomerResource;
use App\Models\Customer;
use App\Actions\UpsertCustomerAction;
use Illuminate\Http\Request;

class CustomerController extends ApiController
{
    public function __construct(
        protected UpsertCustomerAction $upsertCustomerAction,
    ) {}

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');
        $loyaltyPointsMin = $request->get('loyalty_points_min');
        $loyaltyPointsMax = $request->get('loyalty_points_max');
        
        $query = Customer::with('user');

        // Search functionality
        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by loyalty points range
        if ($loyaltyPointsMin) {
            $query->where('loyalty_points', '>=', $loyaltyPointsMin);
        }

        if ($loyaltyPointsMax) {
            $query->where('loyalty_points', '<=', $loyaltyPointsMax);
        }

        $customers = $query->paginate($perPage);
        
        return CustomerResource::collection($customers);
    }

    public function show(Customer $customer)
    {
        $customer->load('user');
        return new CustomerResource($customer);
    }

    public function store(UpsertCustomerRequest $request)
    {
        $dto = UpsertCustomerDto::fromArray($request->validated());
        $customer = $this->upsertCustomerAction->handle($dto);
        return new CustomerResource($customer);
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $dto = UpsertCustomerDto::fromArray($request->validated());
        $customer = $this->upsertCustomerAction->handle($dto, $customer->id);
        return new CustomerResource($customer);
    }

    public function destroy(Customer $customer)
    {
        $user = $customer->user;
        
        $user->delete();

        return response()->noContent();
    }

}

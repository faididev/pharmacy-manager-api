<?php

namespace App\Actions;

use App\DTOs\UpsertCustomerDto;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UpsertCustomerAction
{
    public function handle(UpsertCustomerDto $dto, ?int $customerId = null): Customer
    {
        return DB::transaction(function () use ($dto, $customerId) {

            if ($customerId) {
                // Updating existing customer
                $customer = Customer::findOrFail($customerId);
                $user = $customer->user;

                // Update user data
                $user->update([
                    'name' => $dto->name,
                    'email' => $dto->email,
                    'phone' => $dto->phone ?? $user->phone,
                    'address' => $dto->address ?? $user->address,
                    // optionally update password if passed
                    'password' => isset($dto->password) ? Hash::make($dto->password) : $user->password,
                ]);

                // Update customer fields
                $customer->update([
                    'loyalty_points' => $dto->loyaltyPoints,
                ]);
            } else {
                // Creating new user first
                $user = User::create([
                    'uuid' => Str::uuid(),
                    'name' => $dto->name,
                    'email' => $dto->email,
                    'phone' => $dto->phone ?? null,
                    'address' => $dto->address ?? null,
                    'password' => Hash::make($dto->password),
                ]);

                // Then create customer
                $customer = Customer::create([
                    'user_id' => $user->id,
                    'loyalty_points' => $dto->loyaltyPoints,
                ]);
            }

            return $customer->load('user');
        });
    }
}

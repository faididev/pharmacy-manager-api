<?php

namespace App\Actions\User;

use App\DTOs\UpsertUserDto;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpsertUserAction
{
    public function handle(UpsertUserDto $dto, ?string $userId = null): User
    {
        $payload = $dto->toArray();

        if (isset($payload['password'])) {
            $payload['password'] = Hash::make($payload['password']);
        }

        return $userId
            ? tap(User::findOrFail($userId))->update($payload)->fresh()
            : User::create($payload);
    }
}
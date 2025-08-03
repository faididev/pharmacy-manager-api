<?php

namespace App\DTOs\Auth;

class RegisterUserDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly string $password_confirmation,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            name: $validated['name'],
            email: $validated['email'],
            password: $validated['password'],
            password_confirmation: $validated['password_confirmation'],
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation,
        ];
    }
}
<?php
// app/DTOs/Auth/LoginUserDto.php

namespace App\DTOs\Auth;

class LoginUserDto
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
        public readonly bool $rememberMe = false,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            email: $data['email'],
            password: $data['password'],
        );
    }

    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
}
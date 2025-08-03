<?php


namespace App\DTOs;

class UpsertUserDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly ?string $password = null,
        public readonly ?string $avatar = null,
        public readonly ?\Carbon\Carbon $emailVerifiedAt = null,
        public readonly ?\Carbon\Carbon $lastLoginAt = null,
        public readonly ?string $googleId = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'] ?? null,
            avatar: $data['avatar'] ?? null,
            emailVerifiedAt: $data['email_verified_at'] ?? null,
            lastLoginAt: $data['last_login_at'] ?? null,
            googleId: $data['google_id'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'avatar' => $this->avatar,
            'email_verified_at' => $this->emailVerifiedAt,
            'last_login_at' => $this->lastLoginAt,
            'google_id' => $this->googleId,
        ], fn($value) => !is_null($value));
    }
}
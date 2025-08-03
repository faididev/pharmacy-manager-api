<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasUuid {

    protected static function bootHasUuid(): void
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::orderedUuid(); // or ->uuid()
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function getRouteKey(): mixed
    {
        return $this->uuid;
    }
}
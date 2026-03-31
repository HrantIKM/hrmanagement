<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

final class CurrencyCast implements CastsAttributes
{
    public function __construct(
        private readonly bool $addIcon = false,
        private readonly bool $withFormatted = false,
    ) {
    }

    public function get(Model $model, string $key, mixed $value, array $attributes): string
    {
        if ($this->withFormatted) {
            return $this->getFormatted($model, $key);
        }

        return formattedPrice(
            price: $value,
            addIcon: $this->addIcon
        );
    }

    private function getFormatted(Model $model, string $key): int|string
    {
        $result = 0;
        if (!str_contains($key, '_formatted')) {
            return $result;
        }

        $replacedKey = str_replace('_formatted', '', $key);

        if (isset($model->{$replacedKey})) {
            $result = formattedPrice(
                price: $model->{$replacedKey},
                addIcon: $this->addIcon
            );
        }

        return $result;
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): string
    {
        return $value;
    }
}

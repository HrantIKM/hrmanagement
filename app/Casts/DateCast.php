<?php

namespace App\Casts;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

final class DateCast implements CastsAttributes
{
    public function __construct(
        private readonly bool $withFormatted = false,
        private readonly bool $isDateTime = false,
    ) {
    }

    public function get(Model $model, string $key, mixed $value, array $attributes): string
    {
        if ($this->withFormatted) {
            return $this->getFormatted($model, $key);
        }

        return $this->getCarbonFormattedValue($value);
    }

    private function getFormatted(Model $model, string $key): string
    {
        $result = '';
        if (!str_contains($key, '_formatted')) {
            return $result;
        }

        $replacedKey = str_replace('_formatted', '', $key);

        if (isset($model->{$replacedKey})) {
            $result = $this->getCarbonFormattedValue($model->{$replacedKey});
        }

        return $result;
    }

    private function getCarbonFormattedValue(?string $value): string
    {
        $result = '';
        if ($value) {
            $format = $this->isDateTime ? getDateTimeFormatFront() : getDateFormatFront();

            $result = Carbon::parse($value)->format($format);
        }

        return $result;
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): string
    {
        return $value;
    }
}

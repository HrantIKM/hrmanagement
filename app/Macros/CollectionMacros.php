<?php

namespace App\Macros;

use Closure;

class CollectionMacros
{
    public function getForSelect(): Closure
    {
        return function (string $column = 'name', string $key = 'id') {
            return $this->pluck($column, $key);
        };
    }

    public function pluckColumn(): Closure
    {
        return function (string $column = 'id') {
            return $this->pluck($column)->all();
        };
    }
}

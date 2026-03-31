<?php

namespace App\DTO\Article;

// EXAMPLE
final class ArticleExampleDTO
{
    public function __construct(
        private readonly string $name,
        private readonly string $slug,
    ) {
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
        ];
    }
}

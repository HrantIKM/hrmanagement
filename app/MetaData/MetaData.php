<?php

namespace App\MetaData;

class MetaData
{
    private string $title = '';

    private string $description = '';

    private string $og_image = 'img/logo.jpg';

    private string $og_url = '/';

    private string $og_type = 'website';

    private string $keywords = '';

    public function getDefaultData(): static
    {
        $this->setTitle(config('app.name') ?? trans('meta.home.title'));
        $this->setDescription(trans('meta.home.description'));

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getOgImage(): string
    {
        return url($this->og_image);
    }

    public function setOgImage(string $ogImage): static
    {
        $this->og_image = $ogImage;

        return $this;
    }

    public function getOgUrl(): string
    {
        return urlWithLng($this->og_url);
    }

    public function setOgUrl(string $ogUrl): static
    {
        $this->og_url = $ogUrl;

        return $this;
    }

    public function getOgType(): string
    {
        return $this->og_type;
    }

    public function setOgType(string $ogType): static
    {
        $this->og_type = $ogType;

        return $this;
    }

    public function getKeywords(): string
    {
        return $this->keywords;
    }

    public function setKeywords(string $keywords): static
    {
        $this->keywords = $keywords;

        return $this;
    }
}

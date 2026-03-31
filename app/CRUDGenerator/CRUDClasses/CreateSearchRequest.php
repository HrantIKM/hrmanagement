<?php

namespace App\CRUDGenerator\CRUDClasses;

use App\CRUDGenerator\CRUDGeneratorAbstract;
use Illuminate\Support\Str;

class CreateSearchRequest extends CRUDGeneratorAbstract
{
    public const CONFIG_NAME = 'search_request';

    public function __construct(array $arguments)
    {
        parent::__construct($arguments);

        $this->config = $this->getConfig(self::CONFIG_NAME);
    }

    public function make(): void
    {
        $this->createFolderAndFile($this->getSourceFile($this->config));
    }

    public function getMessageText(): string
    {
        return "{$this->className}SearchRequest";
    }

    protected function stubVariables(): array
    {
        $singularClassName = strtolower(Str::singular($this->className));
        $pluralClassName = strtolower(Str::plural($this->className));

        return [
            'CLASS_NAME' => $this->className,
            'SINGULAR_CLASS_NAME' => $singularClassName,
            'PLURAL_CLASS_NAME' => $pluralClassName,
        ];
    }
}

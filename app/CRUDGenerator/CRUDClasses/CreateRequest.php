<?php

namespace App\CRUDGenerator\CRUDClasses;

use App\CRUDGenerator\CRUDGeneratorAbstract;
use Illuminate\Support\Str;

class CreateRequest extends CRUDGeneratorAbstract
{
    public const CONFIG_NAME = 'request';

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
        return "{$this->className}Request";
    }

    protected function stubVariables(): array
    {
        return [
            'CLASS_NAME' => $this->className,
            'SINGULAR_CLASS_NAME' => strtolower(Str::singular($this->className)),
            'PLURAL_CLASS_NAME' => strtolower(Str::plural($this->className)),
        ];
    }
}

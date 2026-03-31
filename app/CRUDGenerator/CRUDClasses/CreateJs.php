<?php

namespace App\CRUDGenerator\CRUDClasses;

use App\CRUDGenerator\CRUDGeneratorAbstract;
use Illuminate\Support\Str;

class CreateJs extends CRUDGeneratorAbstract
{
    public const CONFIG_NAME = 'js';

    public function __construct(array $arguments)
    {
        parent::__construct($arguments);

        $this->config = $this->getConfig(self::CONFIG_NAME);
    }

    public function make(): void
    {
        foreach ($this->config['files'] as $file) {
            $this->createFolderAndFile($this->getSourceFile($file));
        }
    }

    public function getMessageText(): string
    {
        return ucfirst(self::CONFIG_NAME);
    }

    /**
     * Function to return stub variables.
     */
    protected function stubVariables(): array
    {
        $className = Str::snake($this->className, '-');
        $routeName = Str::snake(Str::plural($this->className), '-');

        return [
            'CLASS_NAME' => $className,
            'ROUTE_NAME' => $routeName,
        ];
    }
}

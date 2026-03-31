<?php

namespace App\CRUDGenerator\CRUDClasses;

use App\CRUDGenerator\CRUDGeneratorAbstract;

class CreateModel extends CRUDGeneratorAbstract
{
    public const CONFIG_NAME = 'model';

    public const CONFIG_NAME_ML = 'model_with_ml';

    public function __construct(array $arguments)
    {
        parent::__construct($arguments);

        $this->config = $this->getConfig($arguments['migrationMl']
            ? self::CONFIG_NAME_ML
            : self::CONFIG_NAME);
    }

    public function make(): void
    {
        $this->createFolderAndFile($this->getSourceFile($this->config));
    }

    public function getMessageText(): string
    {
        return $this->className . ' model';
    }

    protected function stubVariables(): array
    {
        return [
            'CLASS_NAME' => $this->className,
        ];
    }
}

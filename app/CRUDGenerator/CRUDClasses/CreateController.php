<?php

namespace App\CRUDGenerator\CRUDClasses;

use App\CRUDGenerator\CRUDGeneratorAbstract;
use Illuminate\Support\Str;

class CreateController extends CRUDGeneratorAbstract
{
    public const CONFIG_NAME = 'controller';

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
        return "{$this->className}Controller";
    }

    /**
     * Function to return stub variables.
     */
    protected function stubVariables(): array
    {
        $singularClassName = Str::snake(Str::singular($this->className), ' ');
        $pluralClassName = Str::snake(Str::plural($this->className), ' ');
        $pluralClassWithDelimiterName = Str::snake(Str::plural($this->className), '-');
        $folderName = Str::snake($this->className, '-');
        $camelCase = Str::camel(Str::snake($this->className, ' '));

        return [
            'CLASS_NAME' => $this->className,
            'LOWER_CLASS_NAME' => Str::camel($this->className),
            'SINGULAR_CLASS_NAME' => $singularClassName,
            'PLURAL_CLASS_NAME' => $pluralClassName,
            'PLURAL_CLASS_NAME_WITH_DELIMITER' => $pluralClassWithDelimiterName,
            'FOLDER_NAME' => $folderName,
            'CAMEL_CASE_CLASS_NAME' => $camelCase,
        ];
    }
}

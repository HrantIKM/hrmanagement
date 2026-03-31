<?php

namespace App\CRUDGenerator\CRUDClasses;

use App\CRUDGenerator\CRUDGeneratorAbstract;
use Illuminate\Support\Str;

class CreateBlades extends CRUDGeneratorAbstract
{
    public const CONFIG_NAME = 'blades';

    public function __construct(array $arguments)
    {
        parent::__construct($arguments);

        $this->config = $this->getConfig(self::CONFIG_NAME);
    }

    public function make(): void
    {
        foreach ($this->config['files'] as $file) {
            if ($this->hasMl() && 'form.blade' === $file['stub_file_name']) {
                $file['stub_file_name'] = 'form_ml.blade';
            }

            $this->createFolderAndFile($this->getSourceFile($file));
        }
    }

    public function getMessageText(): string
    {
        return ucfirst(self::CONFIG_NAME);
    }

    protected function stubVariables(): array
    {
        $className = Str::snake($this->className, '-');
        $variableName = lcfirst(Str::singular($this->className));
        $routeName = strtolower(Str::plural($className));

        return [
            'CLASS_NAME' => $className,
            'VARIABLE_NAME' => $variableName,
            'ROUTE_NAME' => $routeName,
        ];
    }
}

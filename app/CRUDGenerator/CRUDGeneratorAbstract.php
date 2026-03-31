<?php

namespace App\CRUDGenerator;

use App\CRUDGenerator\Traits\CRUDHelper;
use Symfony\Component\Console\Output\ConsoleOutput;

abstract class CRUDGeneratorAbstract
{
    use CRUDHelper;

    protected array $arguments;

    protected string $className;

    protected array $config;

    public function __construct(array $arguments)
    {
        $this->arguments = $arguments;
        $this->className = $arguments['className'];
    }

    abstract protected function make(): void;

    abstract protected function getMessageText(): string;

    /**
     * Function to show message in terminal.
     */
    public function showMessage(): void
    {
        if ($this->getMessageText()) {
            (new ConsoleOutput())->writeln("<fg=green>{$this->getMessageText()} created successfully!</>");
        }
    }

    /**
     * Function to get CRUD config by key.
     */
    protected function getConfig(string $key): array
    {
        return config("crud.$key");
    }

    /**
     * Map the stub variables present in stub to its value.
     */
    abstract protected function stubVariables(): array;

    /**
     * Function to return stub directory path.
     */
    protected function getStubDirectoryPath(array $fileInfo): string
    {
        return isset($fileInfo['stub_directory_path']) ? $fileInfo['stub_directory_path'] . '/' : '';
    }

    /**
     * Return the stub file path.
     */
    protected function getStubFilePath(array $fileInfo): string
    {
        $path = $this->getStubDirectoryPath($fileInfo);
        $stub_file_name = $fileInfo['stub_file_name'];

        if ($this->arguments['migrationMl'] && isset($fileInfo['stub_file_name_ml'])) {
            $stub_file_name = $fileInfo['stub_file_name_ml'];
        }

        return __DIR__ . "/Stubs/$path$stub_file_name.stub";
    }

    /**
     * Replace the stub variables(key) with the desire value.
     */
    protected function getStubContents(string $stub, array $stubVariables = []): string
    {
        $stubVariables['ROOT_NAMESPACE'] = app()->getNamespace();

        $contents = file_get_contents($stub);
        foreach ($stubVariables as $search => $replace) {
            $contents = str_replace("{{ $search }}", $replace, $contents);
        }

        return $contents;
    }

    /**
     * Get the stub path and the stub variables.
     */
    protected function getSourceFile(?array $fileInfo = null): array
    {
        return [
            'content' => $this->getStubContents($this->getStubFilePath($fileInfo), $this->stubVariables()),
            'variables' => $this->stubVariables(),
            'fileInfo' => $fileInfo,
        ];
    }

    /**
     * Function to check crud has ml.
     */
    public function hasMl(): bool
    {
        if ($this->arguments['migrationMl']) {
            return true;
        }

        return false;
    }
}

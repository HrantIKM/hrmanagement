<?php

namespace App\CRUDGenerator;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\Console\Output\ConsoleOutput;

class CRUDGeneratorInit
{
    protected array $arguments;

    protected ConsoleOutput $consoleOutput;

    public function __construct(array $arguments)
    {
        $this->arguments = $arguments;
        $this->consoleOutput = new ConsoleOutput();
    }

    /**
     * Function to init crud functionality.
     *
     * @throws BindingResolutionException
     */
    public function init(): void
    {
        foreach (glob('app/CRUDGenerator/CRUDClasses/*.php') as $filename) {
            $class = str_replace('/', '\\', ucfirst(explode('.', $filename)[0]));
            $classInstance = app()->makeWith($class, ['arguments' => $this->arguments]);
            $classInstance->make();
            $classInstance->showMessage();
        }

        $this->makeMigration();
        sleep(1);
        $this->makeMigrationMl();
        $this->successMessage();
    }

    /**
     * Function to make migration.
     */
    private function makeMigration(): void
    {
        $tableName = Str::plural(Str::snake($this->arguments['className']));
        Artisan::call("make:migration create_{$tableName}_table");
        $this->consoleOutput->writeln('<fg=green>Migration created successfully</>');
    }

    /**
     * Function to make ml migration.
     */
    private function makeMigrationMl(): void
    {
        if ($this->arguments['migrationMl']) {
            $tableName = Str::snake($this->arguments['className']);
            Artisan::call("make:migration create_{$tableName}_mls_table");
            $this->consoleOutput->writeln('<fg=green>Migration ml created successfully</>');
        }
    }

    /**
     * Function to show success message.
     */
    private function successMessage(): void
    {
        $routeName = Str::snake(Str::plural($this->arguments['className']), '-');
        //        $name = Str::snake(Str::plural($this->arguments['className']), '.');
        $plural = Str::plural($this->arguments['className']);

        $textMessage = "
<fg=yellow>Please don't forget put this codes</>
<fg=blue>1) </>
<fg=green>I{$this->arguments['className']}Repository::class => {$this->arguments['className']}Repository::class,</> <fg=yellow>in RepositoryServiceProvider \$singletons array.</>
<fg=blue>2) </>
<fg=green>// $plural
Route::resource('$routeName', {$this->arguments['className']}Controller::class);
Route::get('$routeName/dataTable/get-list', [{$this->arguments['className']}Controller::class, 'getListData'])->name('$routeName.getListData');</> <fg=yellow>in dashboard.php</>";
        $this->consoleOutput->writeln($textMessage);
    }
}

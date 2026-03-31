<?php

namespace App\Console\Commands\Core;

use App\CRUDGenerator\CRUDGeneratorInit;
use Illuminate\Console\Command;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Str;

class CRUDGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:crud';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '
        Command to generate CRUD:
        It will generate
        1) Request
        2) Controller
        3) Model
        4) ML Model
        5) Repository
        6) Interface
        7) Service
        8) SearchRequest
        9) ModelSearch
        10) Blade creating
        11) Js creating
        12) Migration by class name
        13) Migration ml by class name
    ';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws BindingResolutionException
     */
    public function handle(): void
    {
        $className = $this->ask('What is your class name?(singular). Examples - article, productCategory');
        //        $migration = $this->confirm('Do you want to create migration?', false);
        $migrationMl = $this->confirm('Do you want to create migration for multi language?');

        (new CRUDGeneratorInit([
            'className' => Str::ucfirst($className),
            //            'migration' => $migration,
            'migrationMl' => $migrationMl,
        ]))->init();
    }
}

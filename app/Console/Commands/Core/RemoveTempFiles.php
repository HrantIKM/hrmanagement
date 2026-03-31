<?php

namespace App\Console\Commands\Core;

use App\Services\File\FileTempService;
use Illuminate\Console\Command;

class RemoveTempFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'file:remove-temp-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command removed last days temp files';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Function to remove old temp files.
     */
    public function handle(): void
    {
        FileTempService::removeTempFiles();
    }
}

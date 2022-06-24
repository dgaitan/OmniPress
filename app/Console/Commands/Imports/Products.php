<?php

namespace App\Console\Commands\Imports;

use App\Imports\ProductImport;
use Illuminate\Console\Command;

class Products extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kinja:import-products
                            {file : The file to import}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->output->title('Starting import');
        (new ProductImport)->withOutput($this->output)->import($this->argument('file'));
        $this->output->success('Import successful');
    }
}

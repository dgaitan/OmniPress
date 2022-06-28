<?php

namespace App\Console\Commands\Imports;

use App\Imports\CustomerImport;
use Illuminate\Console\Command;

class Customer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kinja:import-customers
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
        (new CustomerImport)->withOutput($this->output)->import($this->argument('file'));
        $this->output->success('Import successful');
    }
}

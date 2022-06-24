<?php

namespace App\Console\Commands;

use App\Imports\CustomerImport;
use App\Imports\MembershipImport;
use App\Imports\OrderImport;
use App\Imports\ProductImport;
use Illuminate\Console\Command;

class ImportFromCSV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kinja:import
                            {content_type : The content type to import}
                            {file : The file to import}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data from a csv';

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

        $importers = [
            'products' => ProductImport::class,
            'orders' => OrderImport::class,
            'customers' => CustomerImport::class,
            'memberships' => MembershipImport::class,
        ];

        if (! in_array($this->argument('content_type'), array_keys($importers))) {
            $this->error('Content type not valid. Please use products, customers or orders.');

            return 0;
        }

        (new $importers[$this->argument('content_type')])
            ->withOutput($this->output)
            ->import($this->argument('file'));

        $this->output->success('Import successful');
    }
}

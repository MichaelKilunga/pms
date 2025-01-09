<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ContractUpdaterService;

class TriggerContractUpdate extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'contracts:auto-update';

    /**
     * The console command description.
     */
    protected $description = 'Trigger automatic updates for contracts based on date conditions.';

    protected $contractUpdater;

    public function __construct(ContractUpdaterService $contractUpdater)
    {
        parent::__construct();
        $this->contractUpdater = $contractUpdater;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->contractUpdater->updateContracts();
        $this->info('Contracts have been updated successfully!');
    }
}


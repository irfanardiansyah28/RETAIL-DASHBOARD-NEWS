<?php

namespace App\Console\Commands;

use App\Services\FraudDetectionService;
use Illuminate\Console\Command;

class RunFraudScan extends Command
{
    protected $signature = 'fraud:scan';

    protected $description = 'Run Mini AI Fraud Detection scan';

    public function handle(FraudDetectionService $fraudDetectionService)
    {
        $created = $fraudDetectionService->scan();

        $this->info(
            'Fraud scan completed. New risk flags created: '.$created
        );

        return Command::SUCCESS;
    }
}
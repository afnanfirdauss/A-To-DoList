<?php

namespace App\Console\Commands;

use App\Services\DeadlineNotificationService;
use Illuminate\Console\Command;

class CheckDeadlinesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:check-deadlines';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan tasks and send in-app notifications for overdue and approaching deadlines';

    /**
     * Execute the console command.
     */
    public function handle(DeadlineNotificationService $service): int
    {
        $this->info('Checking task deadlines...');

        $created = $service->checkAndSendNotifications();

        $this->info("Completed. {$created} new deadline notifications sent.");

        return Command::SUCCESS;
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanupClicksCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clicks:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete stale clicks based on event tracking criteria';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting click cleanup...');

        // 1. Delete clicks older than 7 days that have NO events in tracked_events
        $sevenDaysAgo = now()->subDays(7);
        $rule1Count = \App\Models\Click::where('created_at', '<', $sevenDaysAgo)
            ->whereDoesntHave('events')
            ->delete();

        $this->info("Rule 1: Deleted $rule1Count clicks with no events older than 7 days.");

        // 2. Delete clicks older than 1 month that HAVE events, but ONLY 'initialevent' (no "other" events)
        $oneMonthAgo = now()->subMonth();
        $rule2Count = \App\Models\Click::where('created_at', '<', $oneMonthAgo)
            ->whereHas('events', function ($query) {
                // Should have at least one event
                $query->where('event_name', 'initialevent');
            })
            ->whereDoesntHave('events', function ($query) {
                // Should not have any event OTHER than 'initialevent'
                $query->where('event_name', '!=', 'initialevent');
            })
            ->delete();

        $this->info("Rule 2: Deleted $rule2Count clicks with only initial events older than 1 month.");

        $this->info('Cleanup completed.');
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeleteOldTemporaryKits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-old-temporary-kits';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cutoffDate = now()->subDays(10);
		TemporarySportsKitRequisition::where('created_at', '<', $cutoffDate)->delete();
		$this->info('Old temporary kits deleted.');
    }
}

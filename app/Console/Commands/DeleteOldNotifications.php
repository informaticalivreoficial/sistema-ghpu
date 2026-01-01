<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteOldNotifications extends Command
{
    protected $signature = 'notifications:cleanup';

    protected $description = 'Remove notificações com mais de 3 meses';    

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = Carbon::now()->subMonths(3);

        $deleted = DB::table('notifications')
            //->whereNotNull('read_at')
            ->where('created_at', '<', $date)
            ->delete();

        $this->info("{$deleted} notificações antigas removidas com sucesso.");

        return Command::SUCCESS;
    }
}

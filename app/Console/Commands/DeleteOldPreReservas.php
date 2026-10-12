<?php

namespace App\Console\Commands;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteOldPreReservas extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'pre-reservas:cleanup';

    /**
     * The console command description.
     */
    protected $description = 'Remove pré-reservas cujo check-in foi há mais de 9 meses';

    public function handle(): int
    {
        $limitDate = Carbon::now()->subMonths(9);

        // 🔥 Remove em todas as empresas: o command roda sem autenticação
        // e o Global Scope do tenant não se aplica — mas deixamos explícito.
        // Comparação só na parte de data (índice em `checkin` continua útil)
        $query = Reservation::withoutGlobalScope('company')
            ->where('checkin', '<', $limitDate->toDateString());

        $count = $query->count();

        $query->delete();

        $this->info("{$count} pré-reservas antigas removidas.");

        return Command::SUCCESS;
    }
}

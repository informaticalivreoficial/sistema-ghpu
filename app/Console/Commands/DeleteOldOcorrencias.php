<?php

namespace App\Console\Commands;

use App\Models\Ocorrencia;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteOldOcorrencias extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'ocorrencias:cleanup';

    /**
     * The console command description.
     */
    protected $description = 'Remove ocorrências com mais de 1 ano';

    public function handle(): int
    {
        $limitDate = Carbon::now()->subYear();

        $count = Ocorrencia::where('created_at', '<', $limitDate)->count();

        Ocorrencia::where('created_at', '<', $limitDate)->delete();

        $this->info("{$count} ocorrências antigas removidas.");

        return Command::SUCCESS;
    }
}

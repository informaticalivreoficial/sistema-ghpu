<?php

namespace App\Console\Commands;

use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MessagesCleanup extends Command
{
    protected $signature = 'messages:cleanup {--dry-run}';
    protected $description = 'Remove conversas e mensagens com mais de 1 ano';

    public function handle(): int
    {
        $limitDate = Carbon::now()->subYear();

        $this->info('🔍 Limpando mensagens anteriores a ' . $limitDate->toDateString());

        $messages = Message::where('updated_at', '<', $limitDate)->get();

        if ($messages->isEmpty()) {
            $this->info('✔ Nenhuma conversa antiga encontrada.');
            return Command::SUCCESS;
        }

        $this->info("📦 Conversas encontradas: {$messages->count()}");

        if ($this->option('dry-run')) {
            $this->warn('⚠ DRY-RUN ativado. Nenhum dado será removido.');
            return Command::SUCCESS;
        }

        foreach ($messages as $message) {
            // Deleta itens e leituras em cascata
            $message->items()->each(function ($item) {
                $item->reads()->delete();
                $item->delete();
            });

            $message->delete();
        }

        $this->info('🗑 Conversas antigas removidas com sucesso.');

        return Command::SUCCESS;
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Corrige o schema drift: a coluna `type` existe em produção mas nunca
     * teve migration. Guardada por hasColumn para não falhar onde já existe.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('ocorrencias', 'type')) {
            Schema::table('ocorrencias', function (Blueprint $table) {
                $table->string('type')->nullable()->after('destinatario');
            });
        }
    }

    public function down(): void
    {
        // Irreversível de propósito: não remove coluna usada em produção.
    }
};

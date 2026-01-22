<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('apartments', function (Blueprint $table) {
            $table->id();
            // Multiempresa
            $table->foreignId('company_id')
                ->constrained()
                ->cascadeOnDelete();

            // Identificação
            $table->string('name'); // Ex: "101", "Suíte Master", "Apto 3"
            $table->string('slug')->index();

            // Capacidade
            $table->unsignedTinyInteger('capacidade_adultos')->default(2);
            $table->unsignedTinyInteger('capacidade_criancas')->default(0);

            // Status
            $table->unsignedTinyInteger('status')->default(0);

            // Observações
            $table->text('observacoes')->nullable();

            // Evita apartamentos duplicados por empresa
            $table->unique(['company_id', 'slug']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apartments');
    }
};

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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();

            // Dados principais
            $table->string('name');
            $table->string('email')->unique();

            // Documentos
            $table->string('cpf', 20)->nullable()->index();
            $table->string('rg', 20)->nullable();

            // Dados pessoais
            $table->date('birthday')->nullable();

            // Contato
            $table->string('whatsapp', 20)->nullable();

            // Endereço
            $table->string('zipcode', 15)->nullable();
            $table->string('street')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('number', 20)->nullable();
            $table->string('complement')->nullable();
            $table->string('city')->nullable();
            $table->string('estate', 2)->nullable(); // UF

            // Extras
            $table->string('status')->default('active')->index();
            $table->text('information')->nullable();

            $table->timestamps();

            $table->unique(['company_id', 'cpf']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};

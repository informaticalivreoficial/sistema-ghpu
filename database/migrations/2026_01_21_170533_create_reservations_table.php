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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            // Multiempresa
            $table->foreignId('company_id')
                ->constrained()
                ->cascadeOnDelete();

            // Cliente
            $table->foreignId('customer_id')
                ->constrained()
                ->cascadeOnDelete();

            // Dados da reserva
            $table->foreignId('apartment_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // 🔥 Texto original recebido
            $table->string('apartamento_texto')->nullable();
            
            $table->date('checkin')->index();
            $table->date('checkout')->index();

            $table->unsignedTinyInteger('adultos')->default(1);
            $table->unsignedTinyInteger('criancas')->default(0);

            $table->string('codigo')->unique();
            $table->string('status')->default('pendente')->index();
            $table->string('information')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};

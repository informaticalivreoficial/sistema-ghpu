<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Dois ajustes de unicidade/not-null na tabela customers:
     *
     * 1. O email era único GLOBALMENTE: se o mesmo hóspede fosse cadastrado em
     *    duas empresas, a API de reservas estourava QueryException (500).
     *    A unicidade correta no multi-tenant é por empresa.
     *    (Relaxar uma restrição nunca invalida dados existentes.)
     *
     * 2. A coluna era NOT NULL, mas a API valida email como 'nullable' —
     *    cliente sem email também derrubava a gravação. Passa a aceitar NULL.
     *    SQL puro: doctrine/dbal não está instalado (Laravel 10).
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropUnique(['email']);
        });

        DB::statement('ALTER TABLE customers MODIFY email VARCHAR(255) NULL');

        Schema::table('customers', function (Blueprint $table) {
            $table->unique(['company_id', 'email']);
        });
    }

    public function down(): void
    {
        DB::statement('UPDATE customers SET email = "" WHERE email IS NULL');

        Schema::table('customers', function (Blueprint $table) {
            $table->dropUnique(['company_id', 'email']);
        });

        DB::statement('ALTER TABLE customers MODIFY email VARCHAR(255) NOT NULL');

        Schema::table('customers', function (Blueprint $table) {
            $table->unique(['email']);
        });
    }
};

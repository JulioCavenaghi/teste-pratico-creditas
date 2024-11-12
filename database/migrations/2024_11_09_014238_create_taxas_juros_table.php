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
        Schema::create('taxas_juros', function (Blueprint $table) {
            $table->id();
            $table->integer('idade_min')->comment('Idade mínima para esta faixa');
            $table->integer('idade_max')->comment('Idade máxima para esta faixa');
            $table->decimal('taxa_juros', 5, 2)->comment('Taxa de juros anual em porcentagem');
            $table->string('moeda', 3)->comment('Moeda utilizada');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taxas_juros');
    }
};

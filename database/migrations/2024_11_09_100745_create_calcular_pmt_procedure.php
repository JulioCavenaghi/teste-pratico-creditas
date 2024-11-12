<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('
            CREATE PROCEDURE CalcularPMT(
            IN valor_emprestimo DECIMAL(18,8),
            IN prazo_em_meses INT,
            IN data_nascimento DATE,
            IN s_moeda VARCHAR(3),
            IN indice DECIMAL(18,8),
            IN taxa_especial DECIMAL(18,8),
            OUT pmt DECIMAL(18,8)
        )
        BEGIN
            DECLARE idade INT;
            DECLARE taxa_juros_anual DECIMAL(18,8);
            DECLARE taxa_mensal DECIMAL(18,8);
            DECLARE fator DECIMAL(18,8);
            DECLARE x INT DEFAULT 1;
            DECLARE taxa_acumulada DECIMAL(18,8);

            SET pmt = 0;
            SET idade = TIMESTAMPDIFF(YEAR, data_nascimento, CURDATE());

            IF taxa_especial IS NOT NULL THEN
                SET taxa_juros_anual = taxa_especial;
            ELSE
                SELECT taxa_juros INTO taxa_juros_anual
                FROM taxas_juros
                WHERE idade BETWEEN idade_min AND idade_max
                AND moeda = s_moeda
                LIMIT 1;
            END IF;

            SET taxa_acumulada = taxa_juros_anual;

            loop_taxa:
            LOOP
                IF x > prazo_em_meses THEN
                    LEAVE loop_taxa;
                END IF;

                IF (x MOD 12 = 0 AND x < prazo_em_meses) THEN
                    SET taxa_juros_anual = taxa_juros_anual + indice;
                    SET taxa_acumulada = taxa_acumulada + taxa_juros_anual;
                END IF;

                SET x = x + 1;
            END LOOP loop_taxa;

            SET taxa_mensal = (taxa_acumulada / prazo_em_meses) / 100;

            SET fator = POWER(1 + taxa_mensal, -prazo_em_meses);

            SET pmt = ROUND((valor_emprestimo * taxa_mensal) / (1 - fator), 2);
        END;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP PROCEDURE IF EXISTS CalcularPMT');
    }
};

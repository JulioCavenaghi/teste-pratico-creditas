<?php

namespace App\Services;

use App\Models\TaxaJuro;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalculoPmtService
{
    public function calcularPMT($valorEmprestimo, $prazoEmMeses, $dataNascimento, $moeda, $jurosVariavel, $taxaEspecial)
    {
        try {

            DB::statement('CALL CalcularPMT(?, ?, ?, ?, ?, ?, @pmt)', [$valorEmprestimo, $prazoEmMeses, $dataNascimento, $moeda, $jurosVariavel, $taxaEspecial]);

            $resultado = DB::select('SELECT @pmt AS valor_parcela');

            $resultado = $resultado[0]->valor_parcela;

            return response($resultado, 200);

        } catch (\Exception $e) {
            return TratamentoErroService::tratarExcecao('Erro ao calcular o PMT.', $e->getMessage(), 500);
        }
    }

    // public function calcularPMT($valorEmprestimo, $prazoEmMeses, $dataNascimento, $moeda, $jurosVariavel, $taxaEspecial=null)
    // {
    //     try {

    //         if($taxaEspecial == null)
    //         {
    //             $idade = Carbon::parse($dataNascimento)->age;

    //             $taxaJurosAnual = TaxaJuro::select('taxa_juros')
    //             ->where('idade_min', '<=', $idade)
    //             ->where('idade_max', '>=', $idade)
    //             ->where('moeda', '=', $moeda)
    //             ->first();

    //             $taxaJurosAnual = $taxaJurosAnual->taxa_juros;

    //         } else {
    //             $taxaJurosAnual = $taxaEspecial;
    //         }

    //         $taxaAcumulada = $taxaJurosAnual;
    //         $x = 1;

    //         while ($x <= $prazoEmMeses) {
    //             if ($x % 12 == 0 && $x < $prazoEmMeses) {
    //                 $taxaJurosAnual += $jurosVariavel;
    //                 $taxaAcumulada += $taxaJurosAnual;
    //             }
    //             $x++;
    //         }

    //         $taxaMensal = ($taxaAcumulada / $prazoEmMeses) / 100;

    //         $fator = pow(1 + $taxaMensal, -$prazoEmMeses);

    //         $resultado = number_format((($valorEmprestimo * $taxaMensal) / (1 - $fator)), 2);

    //         return response($resultado, 200);

    //     } catch (\Exception $e) {
    //         return TratamentoErroService::tratarExcecao('Erro ao calcular o PMT Especial.', $e->getMessage(), 500);
    //     }
    // }
}

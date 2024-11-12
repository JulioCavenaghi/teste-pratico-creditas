<?php

namespace App\Services;

use App\Models\TaxaJuro;
use Carbon\Carbon;
use Exception;

class MoedasService
{
    public static function listaMoedasValidas($moeda, $dataNascimento)
    {
        $idade = Carbon::parse($dataNascimento)->age;

        try {
            $moedasValidas = TaxaJuro::select('moeda')
                ->where('idade_min', '<=', $idade)
                ->where('idade_max', '>=', $idade)
                ->distinct()
                ->pluck('moeda')
                ->toArray();

            if (!in_array($moeda, $moedasValidas)) {
                return TratamentoErroService::tratarExcecao('Erro de validação.','A moeda informada não é suportada. Por favor, forneça uma moeda válida.', 422);
            }

            return response($moedasValidas, 200);

        } catch (Exception $e) {
            return TratamentoErroService::tratarExcecao('Falha ao consultar banco de dados.', $e->getMessage(), 500);
        }

    }

    public static function formatarValor($valor, $moeda)
    {
        switch ($moeda) {
            case 'BRL':
                return 'R$:'.number_format($valor, 2, ',', '.');
            case 'USD':
                return '$:'.number_format($valor, 2, '.', ',');
            case 'EUR':
                return '€:'.number_format($valor, 2, ',', '.');
            default:
                return 'R$:'.number_format($valor, 2, ',', '.');
        }
    }
}

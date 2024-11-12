<?php

namespace App\Http\Controllers;

use App\Mail\EmailCotacao;
use App\Services\CalculoPmtService;
use App\Services\EnvioEmailService;
use App\Services\MoedasService;
use App\Services\TratamentoErroService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class SimulacaoCreditoController extends Controller
{
    public function __construct(
        protected Request $request,
        protected CalculoPmtService $pmtService,
        protected EnvioEmailService $emailService
    ) {}

    public function simularCredito()
    {
        if(isset($this->request->taxaEspecial))
        {
            return $this->processarSimulacao(true);
        } else
        {
            return $this->processarSimulacao();
        }

    }

    private function processarSimulacao($especial = false)
    {
        try {
            $dadosValidados = $this->validarDados();

            $valorEmprestimo = $dadosValidados['valorCotado'];
            $prazoEmMeses = $dadosValidados['prazoEmMeses'];
            $dataNascimento = $dadosValidados['dataNascimento'];
            $moeda = $dadosValidados['moeda'] ?? 'BRL';
            $jurosVariavel = $dadosValidados['jurosVariavel'] ?? 0;
            $taxaEspecial = $especial ? $dadosValidados['taxaEspecial'] : null;

            $moedasValidas = MoedasService::listaMoedasValidas($moeda, $dataNascimento);

            if ($moedasValidas->getStatusCode() != 200) {
                return $moedasValidas;
            }

            $valorParcela = $this->pmtService->calcularPMT($valorEmprestimo, $prazoEmMeses, $dataNascimento, $moeda, $jurosVariavel, $taxaEspecial);

            if ($valorParcela->getStatusCode() != 200) {
                return $valorParcela;
            }

            return $this->prepararResposta($dadosValidados, $valorParcela->content(), $prazoEmMeses, $valorEmprestimo, $moeda);

        } catch (ValidationException $e) {
            return TratamentoErroService::tratarExcecao('Erro de validação.', $e->errors(), 422);
        }
    }

    private function validarDados()
    {
        return $this->request->validate([
            'valorCotado'       => 'required|numeric',
            'prazoEmMeses'      => 'required|integer|min:1',
            'dataNascimento'    => 'required|date',
            'moeda'             => 'string|size:3',
            'taxaEspecial'      => 'numeric',
            'email'             => 'email',
            'jurosVariavel'     => 'numeric',
        ], [
            'valorCotado.required'      => 'O campo valor cotado está vazio.',
            'valorCotado.numeric'       => 'O campo valor cotado deve ser um número válido.',
            'prazoEmMeses.required'     => 'O campo prazo em meses está vazio.',
            'prazoEmMeses.integer'      => 'O campo prazo em meses deve ser um número inteiro.',
            'prazoEmMeses.min'          => 'O campo prazo em meses deve ser pelo menos 1.',
            'dataNascimento.required'   => 'O campo data de nascimento está vazio.',
            'dataNascimento.date'       => 'O campo data de nascimento deve ser uma data válida.',
            'moeda.string'              => 'O campo moeda deve ser uma string válida.',
            'moeda.size'                => 'O campo moeda deve conter 3 dígitos.',
            'taxaEspecial.numeric'      => 'O campo de taxa deve ser um número válido.',
            'email.email'               => 'O campo de email deve ser um valor válido.',
            'jurosVariavel.numeric'     => 'O campo de juros variável deve ser um número válido.',
        ]);
    }

    private function prepararResposta($dadosValidados, $valorParcela, $prazoEmMeses, $valorEmprestimo, $moeda)
    {
        $valorTotal = $valorParcela * $prazoEmMeses;
        $totalJuros = $valorTotal - $valorEmprestimo;
        $mensagemSucesso = 'Simulação realizada com sucesso.';
        $emailEnviado = false;

        if (isset($dadosValidados['email'])) {
            $assunto = 'Simulação de Crédito Realizada.';
            $destinatario = $dadosValidados['email'];
            $envioEmail = $this->emailService->enviarEmail($destinatario, $assunto, $valorParcela, $valorTotal, $totalJuros, $moeda);
            if ($envioEmail->getStatusCode() != 200) {
                return $envioEmail;
            }
            $emailEnviado = true;
            $mensagemSucesso = $envioEmail->content();
        }

        return response()->json([
            'message' => $mensagemSucesso,
            'data' => [
                'valorParcela' => MoedasService::formatarValor($valorParcela, $moeda),
                'valorTotal' => MoedasService::formatarValor($valorTotal, $moeda),
                'totalJuros' => MoedasService::formatarValor($totalJuros, $moeda),
                'emailEnviado' => $emailEnviado
            ]
        ], 200);
    }
}

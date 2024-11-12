<?php

namespace App\Services;

use App\Mail\EmailCotacao;
use Exception;
use Illuminate\Support\Facades\Mail;

class EnvioEmailService
{
    public function enviarEmail($destinatario ,$assunto, $valorParcela, $valorTotal, $totalJuros, $moeda)
    {
        try {
            Mail::to($destinatario)->send(new EmailCotacao($assunto, MoedasService::formatarValor($valorParcela, $moeda), MoedasService::formatarValor($valorTotal, $moeda), MoedasService::formatarValor($totalJuros, $moeda)));
            return response('Simulação realizada com sucesso e o e-mail foi encaminhado com êxito.', 200);

        } catch (Exception $e) {
            return TratamentoErroService::tratarExcecao('Falha ao enviar o email.', $e->getMessage(), 500);
        }
    }
}

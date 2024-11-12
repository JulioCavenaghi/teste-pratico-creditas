<?php

namespace App\Services;

class TratamentoErroService
{
    public static function tratarExcecao($error ,$errorMessage, $cod)
    {
        $strresponse = [
            'error'             => $error,
            'errorMessage'      => $errorMessage
        ];

        return response($strresponse, $cod);
    }
}

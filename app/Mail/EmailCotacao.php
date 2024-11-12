<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailCotacao extends Mailable
{
    use SerializesModels;

    public $subject;
    public $valorParcela;
    public $valorTotal;
    public $totalJuros;

    /**
     * Criação da instância do Mailable.
     *
     * @param string $subject
     * @param float $valorParcela
     * @param float $valorTotal
     * @param float $totalJuros
     */
    public function __construct($subject, $valorParcela, $valorTotal, $totalJuros)
    {
        $this->subject = $subject;
        $this->valorParcela = $valorParcela;
        $this->valorTotal = $valorTotal;
        $this->totalJuros = $totalJuros;
    }

    /**
     * Montar o email.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)
                    ->view('emails.cotacaoEmail')  // Certifique-se de que o nome da view está correto
                    ->with([
                        'valorParcela' => $this->valorParcela,
                        'valorTotal' => $this->valorTotal,
                        'totalJuros' => $this->totalJuros,
                    ]);
    }
}

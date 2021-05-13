<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Orcamento extends Mailable
{
    use Queueable, SerializesModels;
    private $fields;

    /**
     * Create a new message instance.
     *
     * @param array $fields
     */
    public function __construct($fields) {
        $this->subject('Fibrillare - Orçamento feito através do site');
        $this->fields = $fields;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): self {
//        $this->text('vendor.mail.text.orcamento', $this->fields);
        $this->markdown('mail.orcamento', $this->fields);

        return $this->from('no-reply@azulshop.com.br')
            ->view('mail.orcamento', $this->fields);
    }
}

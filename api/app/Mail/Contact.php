<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Contact extends Mailable
{
    use Queueable, SerializesModels;
    private $fields;

    /**
     * Create a new message instance.
     *
     * @param array $fields
     */
    public function __construct($fields) {
        $this->subject('Sorrimaxx - Contato através do site');
        $this->fields = $fields;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): self {
        $this->text('vendor.mail.text.contact', $this->fields);
        $this->markdown('vendor.mail.markdown.contact', $this->fields);

        return $this->from('no-reply@azulshop.com.br')
                    ->view('vendor.mail.html.contact', $this->fields);
    }
}

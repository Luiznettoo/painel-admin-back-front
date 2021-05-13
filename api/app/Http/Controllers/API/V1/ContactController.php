<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\Contact;
use App\Mail\Orcamento;
use Illuminate\Support\Facades\Mail;
use App\Models\CommonText;

class ContactController extends Controller
{
    public function send(Request $request) {
        $this->validateJSON([
            'name'      => 'required|string',
            'email'     => 'required|string',
            'assunto'   => 'nullable',
        ]);
        $email = CommonText::where('identifier','contact.email')->firstOrFail();

        Mail::to($email->content)->send(new Contact($request->json()->all()));

        return response(NULL, 204);
    }

    public function news(Request $request) {
        $this->validateJSON([
            'nome'      => 'required|string',
            'email'     => 'required|string',
            'assunto'   => 'nullable',
        ]);
        $email = CommonText::where('identidy','contact.email');

        Mail::to($email)->send(new Contact($request->json()->all()));

        return response(NULL, 204);
    }

    public function orcamento(Request $request) {
        $this->validateJSON([
            'name'      => 'required|string',
            'email'     => 'required|string',
            'assunto'   => 'nullable',
            'products'  => '',
        ]);

        Mail::to(config('mail.receivers.orcamento'))->send(new Orcamento($request->json()->all()));

        return response(NULL, 204);
    }
}

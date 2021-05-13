@component('mail::layout')

{{-- Header --}}
@slot('header')
    @component('mail::header', ['url' => config('app.url')])
        {{ config('app.name') }}
    @endcomponent
@endslot

{{-- Body --}}
<h3>{{ "$name " }}</h3><br>

Email: {{ $email }}<br>
Telefone: {{$tel}}<br>
Cidade/UF: {{$cidade}}/{{$estado}}<br>
Mensagem:<br>
{{ $msg }}<br>

Produtos:<br>
@foreach ($products as $produto)
    Nome: {{$produto['name']}}<br>
    Quantidade: {{$produto['quantity']}}<br>
    Ref: {{$produto['ref']}}<br>
    Imagem do produto: <br>
    <img src="{{$produto['images']['0']['permalink']}}" style="width:100px;"><br>
    Opções:<br>
    @foreach ($produto['categoria_option'] as $value)
        @if(isset($produto['opcoesImg'][$value['id']]))
            <img src="{{$produto['opcoesImg'][$value['id']]}}" style="width:30px"> {{$value['name']}} - {{$produto['opcoesName'][$value['id']]}}<br>
        @endif
    @endforeach
@endforeach

{{-- Subcopy --}}
@isset($subcopy)
    @slot('subcopy')
        @component('mail::subcopy')
            {{ $subcopy }}
        @endcomponent
    @endslot
@endisset

{{-- Footer --}}
@slot('footer')
    @component('mail::footer')
        © {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
    @endcomponent
@endslot
@endcomponent

@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot

    {{-- Body --}}
    ###{{ "$name $lastname  \n" }}
    Email: {{ "$email  \n" }}
    Telefone: {{ "$tel  \n" }}
    Cidade/UF: {{ "$cidade/$estado  \n" }}
    Mensagem: {{ "  \n" }}

    {{ $msg }}


    Produtos: {{ " \n" }}
    @foreach ($products as $produto)
        Nome: {{ $produto->name }}
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

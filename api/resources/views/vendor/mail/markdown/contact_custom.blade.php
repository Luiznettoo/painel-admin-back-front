@component('mail::layout')
	{{-- Header --}}
	@slot('header')
		@component('mail::header', ['url' => config('app.url')])
			{{ config('app.name') }}
		@endcomponent
	@endslot
	
	{{-- Body --}}
	<p>
		Nome: {{ $name }}<br />
		Email: {{ $email }}<br />
		Telefone: {{ $phone }}<br />
		Tampa e canudo: {{ $question }}<br />
        Quantidade: {{ $quantity }}<br />
	</p>
	
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

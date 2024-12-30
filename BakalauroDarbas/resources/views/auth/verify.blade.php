@extends('layout')
@section('title', 'Patvirtinimas')
@section('content')
<div class="container">
    <div class="form-container">
        <h2 class="form-title">Patvirtinkite savo el. pašto adresą</h2>

        @if (session('resent'))
            <div class="alert alert-success" role="alert">
                {{ __('Nauja patvirtinimo nuoroda buvo išsiųsta į jūsų el. pašto adresą.') }}
            </div>
        @endif

        <p>{{ __('Prieš tęsdami, patikrinkite savo el. paštą dėl patvirtinimo nuorodos.') }}</p>
        <p>{{ __('Jei negavote el. laiško') }},
            <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('paspauskite čia, kad paprašytumėte kitos nuorodos') }}</button>.
            </form>
        </p>
    </div>
</div>
@endsection

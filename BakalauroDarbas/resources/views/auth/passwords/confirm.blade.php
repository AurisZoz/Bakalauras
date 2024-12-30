@extends('layout')
@section('title', 'Slaptažodžio atkūrimas')
@section('content')
<link rel="stylesheet" href="/css/loginstyle.css">
<div class="container">
    <div class="form-container">
        <h2 class="form-title">Slaptažodžio patvirtinimas</h2>

        <p>{{ __('Prašome patvirtinti savo slaptažodį prieš tęsiant.') }}</p>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div class="mb-3 position-relative">
                <label for="password" class="form-label">{{ __('Slaptažodis') }}</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                @error('password')
                    <div class="invalid-feedback"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100">
                {{ __('Patvirtinti slaptažodį') }}
            </button>

            @if (Route::has('password.request'))
                <div class="mt-2 text-center">
                    <a class="btn btn-link" href="{{ route('password.request') }}">
                        {{ __('Pamiršote slaptažodį?') }}
                    </a>
                </div>
            @endif
        </form>
    </div>
</div>
@endsection

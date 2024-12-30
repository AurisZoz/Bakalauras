@extends('layout')
@section('title', 'Slaptažodžio atkūrimas')
@section('content')
<link rel="stylesheet" href="/css/loginstyle.css">

<div class="container">
    <div class="form-container">
        <h2 class="form-title">Slaptažodžio atkūrimas</h2>

        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-3 position-relative">
                <label for="email" class="form-label">{{ __('El. paštas') }}</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                @error('email')
                    <div class="invalid-feedback"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 position-relative">
                <label for="password" class="form-label">{{ __('Naujas slaptažodis') }}</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                @error('password')
                    <div class="invalid-feedback"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 position-relative">
                <label for="password-confirm" class="form-label">{{ __('Patvirtinti slaptažodį') }}</label>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
            </div>

            <button type="submit" class="btn btn-primary w-100">
                {{ __('Atnaujinti slaptažodį') }}
            </button>
        </form>
    </div>
</div>
@endsection

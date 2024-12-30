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
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">{{ __('El. paštas') }}</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="mb-0">
                <button type="submit" class="btn btn-primary">
                    {{ __('Siųsti slaptažodžio atkūrimo nuorodą') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

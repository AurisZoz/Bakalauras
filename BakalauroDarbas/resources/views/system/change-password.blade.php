@extends('layout2')
@section('title', 'Slaptažodžio keitimas')
@section('content')

<link rel="stylesheet" href="/css/settings.css">
<div class="container">
    <h2>
        <img src="/img/key.png" width="50" height="50" class="me-2 align-middle"> Slaptažodžio keitimas
    </h2>

    <div class="form-container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST" novalidate>
            @csrf
            <div class="mb-3 position-relative">
                <label for="current_password" class="form-label">Esamas slaptažodis:</label>
                <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                @error('current_password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3 position-relative">
                <label for="new_password" class="form-label">Naujas slaptažodis:</label>
                <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="new_password" name="new_password" required>
                @error('new_password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3 position-relative">
                <label for="new_password_confirmation" class="form-label">Patvirtinkite naują slaptažodį:</label>
                <input type="password" class="form-control @error('new_password_confirmation') is-invalid @enderror" id="new_password_confirmation" name="new_password_confirmation" required>
                @error('new_password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Atnaujinti slaptažodį</button>
        </form>
    </div>
</div>
@endsection

@extends('layout')
@section('title', 'Prisijungimas prie sistemos „Reabita“')
@section('content')

<link rel="stylesheet" href="/css/loginstyle.css">
<div class="container">

    <div class="form-container">
        <h2 class="form-title">Prisijungimo forma</h2>

        @if(session()->has('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session()->has('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="mb-3">
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="El. paštas" value="{{ old('email') }}">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Slaptažodis">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <a href="{{ route('password.request') }}" class="text-decoration-none">Pamiršote slaptažodį?</a>
            </div>
            <button type="submit" class="btn btn-primary">Prisijungti</button>
        </form>
    </div>
</div>
@endsection
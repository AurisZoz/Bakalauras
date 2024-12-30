@extends('layout')
@section('title', 'Registracija prie sistemos „Reabita“')
@section('content')

<link rel="stylesheet" href="/css/loginstyle.css">

<div class="container">

    @if(session()->has('error'))
        <div class="alert alert-danger d-block">{{ session('error') }}</div>
    @endif

    <div class="form-container">
        <h2 class="form-title">Registracijos forma</h2>
        <form action="{{ route('registration.post') }}" method="POST">
            @csrf
            <div class="mb-3 position-relative">
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Vardas" value="{{ old('name') }}">
                @error('name')
                    <div class="invalid-feedback"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3 position-relative">
                <input type="text" class="form-control @error('surname') is-invalid @enderror" id="surname" name="surname" placeholder="Pavardė" value="{{ old('surname') }}">
                @error('surname')
                    <div class="invalid-feedback"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3 position-relative">
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="El. paštas" value="{{ old('email') }}">
                @error('email')
                    <div class="invalid-feedback"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3 position-relative">
                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" placeholder="Telefono numeris" value="{{ old('phone') }}">
                @error('phone')
                    <div class="invalid-feedback"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3 position-relative">
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Slaptažodis">
                @error('password')
                    <div class="invalid-feedback"><i class="fa fa-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Registruotis</button>
        </form>
    </div>
</div>

<script src="/js/registration.js"></script>

@endsection

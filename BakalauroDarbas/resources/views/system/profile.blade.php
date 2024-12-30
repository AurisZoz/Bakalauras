@extends('layout2')
@section('title', 'Naudotojo profilis')
@section('content')

<link rel="stylesheet" href="/css/settings.css">
<div class="container">
    <h2><img src="/img/userprofile.png" width="50" height="50" class="me-2 align-middle"> Profilio informacija</h2>
    <div class="form-container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" novalidate>
            @csrf
            <div class="mb-3 position-relative">
                <label for="name" class="form-label">Vardas:</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3 position-relative">
                <label for="surname" class="form-label">Pavardė:</label>
                <input type="text" class="form-control @error('surname') is-invalid @enderror" id="surname" name="surname" value="{{ old('surname', $user->surname) }}" required>
                @error('surname')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3 position-relative">
                <label for="phone" class="form-label">Telefono numeris:</label>
                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required>
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3 position-relative">
                <label for="email" class="form-label">Elektroninis paštas:</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Išsaugoti</button>
        </form>
    </div>
</div>
<script src="/js/registration.js"></script>
@endsection

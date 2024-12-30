@extends('layout2')
@section('title', 'Atnaujinti Paskyrimą')
@section('content')

<link rel="stylesheet" href="{{ asset('css/appointment.css') }}">
<div class="container">
    <h1>Atnaujinti Paskyrimą</h1>

    <form action="{{ route('appointment.update', $appointment->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="user_id" class="form-label">Pasirinkite naudotoją</label>
            @if($users->count() > 5)
                <input type="text" id="user_search" class="form-control mb-2" placeholder="Ieškoti naudotojo...">
            @endif
            <select id="user_id" name="user_id" class="form-select" required>
                <option value="{{ $appointment->user_id }}" selected>
                    {{ $appointment->user->name }} {{ $appointment->user->surname }}
                </option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" data-search="{{ strtolower($user->name . ' ' . $user->surname . ' ' . $user->phone) }}" 
                             data-name="{{ $user->name }}" data-surname="{{ $user->surname }}" data-phone="{{ $user->phone }}">
                        {{ $user->name }} {{ $user->surname }} ({{ $user->phone }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="plan_id" class="form-label">Pasirinkite planą</label>
            @if($plans->count() > 5)
                <input type="text" id="plan_search" class="form-control mb-2" placeholder="Ieškoti plano...">
            @endif
            <select id="plan_id" name="plan_id" class="form-select" required>
                <option value="{{ $appointment->plan_id }}" selected>{{ $appointment->plan->title }}</option>
                @foreach($plans as $plan)
                    <option value="{{ $plan->id }}" data-search="{{ strtolower($plan->title) }}" data-id="{{ $plan->id }}" data-title="{{ $plan->title }}">
                        ID: {{ $plan->id }} - {{ $plan->title }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="start_date" class="form-label">Reabilitacijos pradžios data</label>
            <input type="date" id="start_date" name="start_date" class="form-control" value="{{ $appointment->start_date }}" required>
        </div>
        <div class="mb-3">
            <label for="end_date" class="form-label">Reabilitacijos pabaigos data</label>
            <input type="date" id="end_date" name="end_date" class="form-control" value="{{ $appointment->end_date }}" required>
        </div>
        <div class="mb-3">
            <label for="comments" class="form-label">Komentaras</label>
            <textarea id="comments" name="comments" class="form-control" rows="4" placeholder="Įrašykite komentarą">{{ $appointment->comments }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Atnaujinti</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const userSearch = document.getElementById('user_search');
        const userSelect = document.getElementById('user_id');
        if (userSearch) {
            userSearch.addEventListener('input', function () {
                const searchValue = this.value.toLowerCase();
                Array.from(userSelect.options).forEach(option => {
                    const searchData = option.getAttribute('data-search');
                    option.style.display = searchData && searchData.includes(searchValue) ? '' : 'none';
                });
            });
        }

        const planSearch = document.getElementById('plan_search');
        const planSelect = document.getElementById('plan_id');
        if (planSearch) {
            planSearch.addEventListener('input', function () {
                const searchValue = this.value.toLowerCase();
                Array.from(planSelect.options).forEach(option => {
                    const searchData = option.getAttribute('data-search');
                    option.style.display = searchData && searchData.includes(searchValue) ? '' : 'none';
                });
            });
        }
    });
</script>

@endsection

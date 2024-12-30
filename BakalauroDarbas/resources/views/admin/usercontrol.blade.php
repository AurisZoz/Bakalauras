@extends('layout2')

@section('content')
<link rel="stylesheet" href="{{ asset('css/usercontrol.css') }}">

<div class="container mt-4 text-center">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

<div class="container mt-4 text-center">
    <h2 class="text-center">
        <img src="/img/usercontrol.png" width="50" height="50" class="me-2 align-middle">
        Naudotojų valdymas
    </h2>
    <div class="d-flex justify-content-center mt-4">
        <form id="searchForm" class="position-relative" style="width: 100%; max-width: 500px;">
            <div class="input-group">
                <span class="input-group-text">
                    <img src="/img/searchbar.png" width="20" height="20" alt="Paieška">
                </span>
                <input type="text" id="searchBar" class="form-control" placeholder="Ieškoti naudotojų pagal vardą, pavardę ar el. paštą...">
            </div>
            <div id="searchDropdown" class="dropdown-menu w-100"></div>
        </form>
    </div>
</div>

<script src="{{ asset('js/usercontrol.js') }}"></script>
@endsection

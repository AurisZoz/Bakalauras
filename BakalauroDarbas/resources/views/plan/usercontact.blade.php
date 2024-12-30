@extends('layout2')
@section('title', 'Kontaktų sąrašas')
@section('content')

<link rel="stylesheet" href="{{ asset('css/all-plans-style.css') }}">

<div class="container mt-4">
    <h1 class="mb-4 text-center">
        <img src="{{ asset('img/contacts.png') }}" width="50" height="50" class="me-2 align-middle">
        Kontaktų sąrašas
    </h1>

    <div class="form-wrapper p-4 shadow rounded bg-white mx-auto" style="max-width: 800px;">
        <form action="{{ route('user.contacts') }}" method="GET" class="mb-4" id="searchForm">
            <div class="input-group">
                <span class="input-group-text" id="basic-addon1">
                    <img src="{{ asset('img/searchbar.png') }}" width="20" height="20">
                </span>
                <input type="text" name="search" class="form-control" placeholder="Ieškoti kontakto" value="{{ request('search') }}" id="searchInput">
            </div>
        </form>

        <table class="table table-hover">
            <thead>
                <tr>
                <th style="width: 50px; text-align: center;">#</th>
                    <th>
                        <a href="{{ route('user.contacts', ['sort' => 'name', 'order' => request('sort') === 'name' && request('order') === 'asc' ? 'desc' : 'asc']) }}" class="text-dark text-decoration-none">
                            Vardas
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('user.contacts', ['sort' => 'surname', 'order' => request('sort') === 'surname' && request('order') === 'asc' ? 'desc' : 'asc']) }}" class="text-dark text-decoration-none">
                            Pavardė
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('user.contacts', ['sort' => 'phone', 'order' => request('sort') === 'phone' && request('order') === 'asc' ? 'desc' : 'asc']) }}" class="text-dark text-decoration-none">
                            Telefono numeris
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('user.contacts', ['sort' => 'role', 'order' => request('sort') === 'role' && request('order') === 'asc' ? 'desc' : 'asc']) }}" class="text-dark text-decoration-none">
                            Pareigos
                        </a>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($contacts as $index => $contact)
                <tr>
                <td style="width: 50px; text-align: center;">{{ $loop->iteration }}</td>
                    <td>{{ $contact->name }}</td>
                    <td>{{ $contact->surname }}</td>
                    <td>{{ $contact->phone }}</td>
                    <td>
                        @if ($contact->role === 'doctor')
                            Gydytojas
                        @elseif ($contact->role === 'admin')
                            Administratorius
                        @else
                            Naudotojas
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-3">
        @if ($contacts->hasPages())
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <li class="page-item {{ $contacts->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $contacts->previousPageUrl() }}" aria-label="Ankstesnis">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>

                    @foreach ($contacts->getUrlRange(1, $contacts->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $contacts->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    <li class="page-item {{ $contacts->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $contacts->nextPageUrl() }}" aria-label="Sekantis">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        @endif
    </div>
</div>
@endsection

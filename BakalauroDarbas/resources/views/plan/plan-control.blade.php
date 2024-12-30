@extends('layout2')
@section('title', 'Mano reabilitacijos planai')
@section('content')

<link rel="stylesheet" href="{{ asset('css/all-plans-style.css') }}">
<script src="{{ asset('js/search.js') }}"></script>

<div class="container mt-4">
    <h2><img src="/img/searchlogo.png" width="50" height="50" class="me-2 align-middle"> Mano reabilitacijos planai</h2>
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <div class="form-wrapper">
        <form action="{{ route('plan.index') }}" method="GET" class="mb-4" id="searchForm">
            <div class="input-group">
                <span class="input-group-text" id="basic-addon1">
                    <img src="/img/searchbar.png" width="20" height="20">
                </span>
                <input type="text" name="search" class="form-control" placeholder="Ieškoti plano..." value="{{ request('search') }}" id="searchInput" data-table-id="plans-table">
            </div>
        </form>

        <table class="table table-hover mt-4">
            <thead>
                <tr>
                    <th style="width: 50px; text-align: center;">#</th>
                    <th style="text-align: left;">
                        <a href="{{ route('plan.index', ['sort_by' => 'title', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}" style="color: black; text-decoration: none;">
                            Pavadinimas
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('plan.index', ['sort_by' => 'created_at', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}" style="color: black; text-decoration: none;">
                            Sukūrimo data
                        </a>
                    </th>
                    <th>Veiksmai</th>
                </tr>
            </thead>
            <tbody id="plans-table">
                @foreach($plans as $index => $plan)
                <tr>
                    <td style="width: 50px; text-align: center;">{{ ($plans->currentPage() - 1) * $plans->perPage() + $index + 1 }}</td>
                    <td>
                        <span class="align-middle">{{ $plan->title }}</span>
                    </td>
                    <td>{{ $plan->created_at->format('Y-m-d') }}</td>
                    <td>
                        <a href="{{ route('plan.show', ['id' => $plan->id, 'referrer' => request()->fullUrl()]) }}" class="btn btn-info btn-sm">
                            <i class="fa fa-eye"></i> Peržiūrėti
                        </a>
                        <a href="{{ route('plan.edit', $plan->id) }}" class="btn btn-success btn-sm">
                            <i class="fa fa-pencil-alt"></i> Atnaujinti
                        </a>
                        <form action="{{ route('plan.destroy', $plan->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Ar tikrai norite ištrinti šį planą?');">
                                <i class="fa fa-trash"></i> Ištrinti
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div> 

    <div class="d-flex justify-content-center mt-3">
        @if ($plans->hasPages())
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item {{ $plans->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $plans->previousPageUrl() }}" aria-label="Ankstesnis">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Ankstesnis</span>
                        </a>
                    </li>
                    @foreach ($plans->getUrlRange(1, $plans->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $plans->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach
                    <li class="page-item {{ $plans->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $plans->nextPageUrl() }}" aria-label="Sekantis">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Sekantis</span>
                        </a>
                    </li>
                </ul>
            </nav>
        @endif
    </div>
</div>
@endsection

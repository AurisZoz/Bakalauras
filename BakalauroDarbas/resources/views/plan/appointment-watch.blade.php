@extends('layout2')
@section('title', 'Paskyrimų peržiūra')
@section('content')

<link rel="stylesheet" href="{{ asset('css/all-plans-style.css') }}">
<script src="{{ asset('js/search.js') }}"></script>

<div class="container mt-4">
    <h2>
        <img src="/img/patientwatch.png" width="50" height="50" class="me-2 align-middle">
        Paskyrimų peržiūra
    </h2>
    <div class="form-wrapper">
        <form action="{{ route('appointments.watch') }}" method="GET" class="mb-4" id="searchForm">
            <div class="input-group">
                <span class="input-group-text">
                    <img src="/img/searchbar.png" width="20" height="20">
                </span>
                <input type="text" name="search" class="form-control" placeholder="Ieškoti paciento ar plano" value="{{ request('search') }}" id="searchInput" data-table-id="appointments-table">
            </div>
        </form>
        <table class="table table-hover mt-4">
            <thead>
                <tr>
                    <th style="width: 50px; text-align: center;">#</th>
                    <th>
                        <a href="{{ route('appointments.watch', ['sort_by' => 'patient', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}" style="color: black; text-decoration: none;">
                            Pacientas
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('appointments.watch', ['sort_by' => 'plan_title', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}" style="color: black; text-decoration: none;">
                            Planas
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('appointments.watch', ['sort_by' => 'start_date', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}" style="color: black; text-decoration: none;">
                            Pradžios data
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('appointments.watch', ['sort_by' => 'end_date', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}" style="color: black; text-decoration: none;">
                            Pabaigos data
                        </a>
                    </th>
                    <th>Komentaras</th>
                    <th>Veiksmai</th>
                </tr>
            </thead>
            <tbody id="appointments-table">
                @foreach($appointments as $index => $appointment)
                    <tr>
                        <td style="width: 50px; text-align: center;">{{ $loop->iteration }}</td>
                        <td>{{ $appointment->user->name }} {{ $appointment->user->surname }}</td>
                        <td>{{ $appointment->plan->title }}</td>
                        <td>{{ \Carbon\Carbon::parse($appointment->start_date)->format('Y-m-d') }}</td>
                        <td>{{ \Carbon\Carbon::parse($appointment->end_date)->format('Y-m-d') }}</td>
                        <td>{{ $appointment->comments ?? 'Nėra komentarų' }}</td>
                        <td>
                            <a href="{{ route('plan.show', $appointment->plan->id) }}" class="btn btn-info btn-sm">
                                <i class="fa fa-eye"></i> Peržiūrėti
                            </a>
                            <a href="{{ route('appointment.edit', $appointment->id) }}" class="btn btn-success btn-sm">
                                <i class="fa fa-pencil-alt"></i> Atnaujinti
                            </a>
                            <form action="{{ route('appointment.destroy', $appointment->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete()">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
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
        @if ($appointments->hasPages())
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item {{ $appointments->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $appointments->previousPageUrl() }}" aria-label="Ankstesnis">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Ankstesnis</span>
                        </a>
                    </li>

                    @foreach ($appointments->getUrlRange(1, $appointments->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $appointments->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    <li class="page-item {{ $appointments->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $appointments->nextPageUrl() }}" aria-label="Sekantis">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Sekantis</span>
                        </a>
                    </li>
                </ul>
            </nav>
        @endif
    </div>
</div>

<script>
    function confirmDelete() {
        return confirm('Ar tikrai norite ištrinti šį paskyrimą?');
    }
</script>

@endsection

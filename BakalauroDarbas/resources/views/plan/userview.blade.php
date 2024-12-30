@extends('layout2')
@section('title', 'Reabilitacijos planai')
@section('content')

<link rel="stylesheet" href="{{ asset('css/all-plans-style.css') }}">
<div class="container text-center">
    <h1 class="d-flex justify-content-center align-items-center">
        <img src="/img/rehabilitation.png" width="50" height="50" class="me-2 align-middle">
        Reabilitacijos Planai
    </h1>

    @if($appointments->isEmpty())
        <p style="font-size: 1.5rem; color: #333; margin-top: 20px;">
            Šiuo metu jūs neturite reabilitacijos planų paskyrimų.
        </p>
    @else
        <div class="form-wrapper mt-4">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Planas</th>
                        <th scope="col">Pradžios data</th>
                        <th scope="col">Pabaigos data</th>
                        <th scope="col">Komentaras</th>
                        <th scope="col">Paskyrė</th> 
                        <th scope="col">Veiksmai</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->plan->title }}</td>
                            <td>{{ \Carbon\Carbon::parse($appointment->start_date)->format('Y-m-d') }}</td>
                            <td>{{ \Carbon\Carbon::parse($appointment->end_date)->format('Y-m-d') }}</td>
                            <td>{{ $appointment->comments ?? 'Nėra komentarų' }}</td>
                            <td>{{ $appointment->assignedBy->name }} {{ $appointment->assignedBy->surname }}</td> 
                            <td>
                                <a href="{{ route('plan.show', $appointment->plan->id) }}" class="btn btn-info btn-sm">
                                    <i class="fa fa-eye"></i> Peržiūrėti
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection

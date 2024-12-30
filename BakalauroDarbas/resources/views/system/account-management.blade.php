@extends('layout2')
@section('title', 'Paskyros valdymas')
@section('content')

<link rel="stylesheet" href="/css/settings.css">
<div class="container">
    <h2>
        <img src="/img/accountsetting.png" width="50" height="50" class="me-2 align-middle"> Paskyros valdymas
    </h2>

    <div class="form-container">
        <h4>Naudotojo anketos ištrynimo sąlygos</h4>
        <ol class="mt-3 mb-4">
            <li>Ištrinant paskyrą, jūs sutinkate, kad jūsų visi duomenys bus ištrinti.</li>
            <li>Ištrintų duomenų atnaujinimas negalimas – ištrynus paskyrą jos susigrąžinti nebeišeis.</li>
        </ol>

        <form action="{{ route('account.delete') }}" method="POST" onsubmit="return confirm('Ar tikrai norite ištrinti savo paskyrą?');" class="text-center">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger mt-3">Ištrinti paskyrą</button>
        </form>
    </div>
</div>
@endsection

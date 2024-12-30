@extends('layout2')
@section('title', 'Naudotojų duomenys')

@section('content')
<link rel="stylesheet" href="{{ asset('css/usercontrolshow.css') }}">

<div class="container mt-4">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="d-flex justify-content-center align-items-center text-center mb-4" style="width: 100%;">
        <img src="{{ asset('img/personal-information.png') }}" style="width: 50px; height: auto; border-radius: 8px; margin-right: 20px;">
        <h2 class="text-center">Naudotojo duomenys</h2>
    </div>

    <div class="d-flex justify-content-center">
        <div class="user-info-section card mt-4" style="width: 100%; max-width: 600px;">
            <div class="card-body">
                <div class="user-photo mb-3 text-center">
                    <img src="{{ $user->profile_photo ? $user->profile_photo : asset('img/profileuser.png') }}"
                         style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                </div>

                <form id="editUserForm" method="POST" action="{{ route('usercontrol.update', $user->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-row">
                        <div class="form-group">
                            <label for="userID">ID:</label>
                            <input type="text" id="userID" class="form-control form-control-sm" readonly value="{{ $user->id }}">
                        </div>
                        <div class="form-group">
                            <label for="name">Vardas:</label>
                            <input type="text" name="name" id="name" class="form-control form-control-sm" value="{{ old('name', $user->name) }}">
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="surname">Pavardė:</label>
                            <input type="text" name="surname" id="surname" class="form-control form-control-sm" value="{{ old('surname', $user->surname) }}">
                            @error('surname')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">El. paštas:</label>
                            <input type="email" name="email" id="email" class="form-control form-control-sm" value="{{ old('email', $user->email) }}">
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Telefonas:</label>
                            <input type="text" name="phone" id="phone" class="form-control form-control-sm" value="{{ old('phone', $user->phone) }}">
                            @error('phone')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="role">Rolė:</label>
                            <select name="role" id="role" class="form-control form-control-sm" required>
                                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>user</option>
                                <option value="doctor" {{ $user->role == 'doctor' ? 'selected' : '' }}>doctor</option>
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>admin</option>
                            </select>
                            @error('role')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <button type="submit" id="saveUserButton" class="btn btn-primary btn-block">Išsaugoti</button>
                        </div>
                    </div>
                </form>

                <form action="{{ route('usercontrol.destroy', $user->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-block" onclick="return confirmDelete();">Ištrinti naudotoją</button>
                </form>
            </div>
        </div>
    </div>





    <div class="text-center mt-5 mb-4">
        <img src="{{ asset('img/searchlogo.png') }}" width="40" height="40" class="me-2 align-middle">
        <h3 class="d-inline align-middle">Naudotojo sukurti planai</h3>
    </div>

    <div class="user-plans-section card" style="width: 100%; max-width: 1200px;">
        <div class="p-4">
            <form action="{{ route('usercontrol.show', $user->id) }}" method="GET" class="mb-4" id="searchForm">
                <div class="input-group" style="max-width: 400px; margin: 0 auto;">
                    <span class="input-group-text">
                        <img src="{{ asset('img/searchbar.png') }}" width="20" height="20">
                    </span>
                    <input type="text" name="search" class="form-control" placeholder="Ieškoti plano..." value="{{ request('search') }}">
                </div>
            </form>
        </div>

        <div class="card-body" style="overflow-y: auto;">
            @if($plans->count() > 0)
                <table class="table">
                    
                <thead>
    <tr>
        <th>
            <a href="{{ route('usercontrol.show', ['id' => $user->id, 'sort' => 'id', 'order' => request('order') === 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}" class="text-dark text-decoration-none">
                ID
            </a>
        </th>
        <th>
            <a href="{{ route('usercontrol.show', ['id' => $user->id, 'sort' => 'title', 'order' => request('order') === 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}" class="text-dark text-decoration-none">
                Plano pavadinimas
            </a>
        </th>
        <th>
            <a href="{{ route('usercontrol.show', ['id' => $user->id, 'sort' => 'created_at', 'order' => request('order') === 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}" class="text-dark text-decoration-none">
                Sukūrimo data
            </a>
        </th>
        <th>Veiksmai</th>
    </tr>
</thead>

                    <tbody>
                        @foreach($plans as $plan)
                            <tr>
                                <td>{{ $plan->id }}</td>
                                <td>{{ $plan->title }}</td>
                                <td>{{ $plan->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route('plan.show', $plan->id) }}" class="btn btn-info btn-sm"><i class="fa fa-eye"></i> Peržiūrėti</a>
                                    <a href="{{ route('plan.edit', $plan->id) }}" class="btn btn-success btn-sm"><i class="fa fa-pencil-alt"></i> Atnaujinti</a>
                                    <form action="{{ route('plan.destroy', $plan->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirmDeletePlan();"><i class="fa fa-trash"></i> Ištrinti</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center mt-3">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            <li class="page-item {{ $plans->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $plans->previousPageUrl() }}" aria-label="Ankstesnis">
                                    <span aria-hidden="true">&laquo;</span>
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
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            @else
                <p class="text-center">Šis naudotojas neturi sukurtų planų.</p>
            @endif
        </div>
    </div>
</div>

<script>
    document.getElementById('editUserForm').addEventListener('submit', function(event) {
        if (!confirm("Ar tikrai norite atnaujinti naudotojo duomenis?")) {
            event.preventDefault();
        }
    });

    function confirmDelete() {
        return confirm("Ar tikrai norite ištrinti šį naudotoją?");
    }

    function confirmDeletePlan() {
        return confirm("Ar tikrai norite ištrinti šį planą?");
    }
</script>
@endsection

@extends('layout2')
@section('title', 'Naudotojų informacija')
@section('content')

<link rel="stylesheet" href="{{ asset('css/all-plans-style.css') }}">
<div class="container mt-4">
    <h2 class="mb-4">
        <img src="/img/users.png" width="50" height="50" class="me-2 align-middle">Naudotojų informacija
    </h2>   
    
    <div class="form-wrapper p-4 shadow rounded bg-white">
        <form method="GET" action="{{ route('admin.userProfiles') }}" class="mb-4">
            <div class="input-group">
                <span class="input-group-text" id="basic-addon1">
                    <img src="/img/searchbar.png" width="20" height="20">
                </span>
                <input type="text" class="form-control" id="search-bar" name="search" value="{{ request('search') }}" placeholder="Ieškoti naudotojo..." aria-describedby="basic-addon1">
            </div>
        </form>

        <table class="table table-hover">
        <thead>
    <tr>
    <th style="width: 50px; text-align: center;">
            <a href="{{ route('admin.userProfiles', ['sort' => 'id', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}" style="color: black; text-decoration: none;">
                ID
            </a>
        </th>
        <th>
            <a href="{{ route('admin.userProfiles', ['sort' => 'name', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}" style="color: black; text-decoration: none;">
                Vardas
            </a>
        </th>
        <th>
            <a href="{{ route('admin.userProfiles', ['sort' => 'surname', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}" style="color: black; text-decoration: none;">
                Pavardė
            </a>
        </th>
        <th>
            <a href="{{ route('admin.userProfiles', ['sort' => 'phone', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}" style="color: black; text-decoration: none;">
                Telefono numeris
            </a>
        </th>
        <th>
            <a href="{{ route('admin.userProfiles', ['sort' => 'email', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}" style="color: black; text-decoration: none;">
                El. paštas
            </a>
        </th>
        <th>
            <a href="{{ route('admin.userProfiles', ['sort' => 'role', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}" style="color: black; text-decoration: none;">
                Rolė
            </a>
        </th>
        <th>Veiksmai</th>
    </tr>
</thead>

            <tbody id="user-table">
                @foreach($users as $user)
                    <tr>
                    <td style="width: 50px; text-align: center;">{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->surname }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role }}</td>
                        <td>
                            <a href="{{ url('/admin/usercontrol/' . $user->id) }}" class="btn btn-primary">
                                <i class="fas fa-eye"></i> Peržiūrėti
                            </a> 
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <li class="page-item {{ $users->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $users->previousPageUrl() }}" aria-label="Ankstesnis">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                    <li class="page-item {{ $page == $users->currentPage() ? 'active' : '' }}">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                @endforeach
                <li class="page-item {{ $users->hasMorePages() ? '' : 'disabled' }}">
                    <a class="page-link" href="{{ $users->nextPageUrl() }}" aria-label="Sekantis">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>

<script>
    document.getElementById('search-bar').addEventListener('input', function() {
        const query = this.value.toLowerCase();
        const rows = document.querySelectorAll('#user-table tr');

        rows.forEach(row => {
            const name = row.children[1].textContent.toLowerCase();
            const surname = row.children[2].textContent.toLowerCase();
            const id = row.children[0].textContent.toLowerCase(); 

            if (name.includes(query) || surname.includes(query) || id.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>

@endsection

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<nav id="sidebar" class="text-light p-3">
    <div class="d-flex align-items-center flex-column position-relative">
        @auth
            <div class="position-relative mb-2">
                <img id="profile-photo" src="{{ Auth::user()->profile_photo_url }}" onclick="document.getElementById('upload-profile-photo').click()">
                <div class="delete-btn-container">
                    <button class="delete-profile-btn" onclick="deletePhoto()">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>
            <div class="user-info text-left">
                <div class="user-name">{{ Auth::user()->name }}</div>
                <div class="user-surname font-weight-bold">{{ Auth::user()->surname }}</div>
            </div>
        @else
            <div class="user-name">Sidebar</div>
        @endauth
    </div>
    <input type="file" id="upload-profile-photo" style="display: none;" accept="image/*" onchange="uploadPhoto()">
    <hr class="divider">
    <ul class="nav flex-column mt-2">
    @auth
    @if(in_array(Auth::user()->role, ['admin']))
        <li class="nav-item">
            <a class="nav-link dropdown-toggle {{ request()->is('admin*') ? 'active' : '' }}" id="admin-btn" href="#" data-bs-toggle="collapse" data-bs-target="#adminDropdown" aria-expanded="false" aria-controls="adminDropdown">
                <img src="/img/admin.png">
                <span>Admin</span>
            </a>
            <div class="collapse" id="adminDropdown">
                <ul class="list-unstyled ps-3">
                    <li>
                        <a class="nav-link {{ request()->is('admin/user-profiles') ? 'active' : '' }}" href="/admin/user-profiles">
                            <img src="/img/users.png">Naudotojai</a></li>
                    <li>
                        <a class="nav-link {{ request()->is('admin/usercontrol') ? 'active' : '' }}" href="/admin/usercontrol">
                            <img src="/img/usercontrol.png">Valdymas</a></li>
                </ul>
            </div>
        </li>
        @endif
        @endauth
        @auth
        @if(in_array(Auth::user()->role, ['admin', 'doctor']))
        <li class="nav-item">
            <a class="nav-link dropdown-toggle {{ request()->is('appointment*') ? 'active' : '' }}" id="appointment-btn" href="#" data-bs-toggle="collapse" data-bs-target="#appointmentDropdown" aria-expanded="false" aria-controls="appointmentDropdown">
                <img src="/img/appointment.png">
                <span>Paskyrimas</span>
            </a>
            <div class="collapse" id="appointmentDropdown">
                <ul class="list-unstyled ps-3">
                    <li>
                        <a class="nav-link {{ request()->is('appointments/create') ? 'active' : '' }}" href="/appointments/create">
                            <img src="/img/appointment2.png"> Paskirti</a>
                    </li>
                    <li>
                        <a class="nav-link {{ request()->is('appointments/watch') ? 'active' : '' }}" href="/appointments/watch">
                            <img src="/img/patientwatch.png"> Pacientai</a>
                    </li>
                </ul>
            </div>
        </li>
        @endif
        @endauth
        <li class="nav-item">
            <a class="nav-link {{ request()->is('user/view') ? 'active' : '' }}" href="{{ route('user.view') }}">
                <img src="/img/rehabilitation.png"> 
                <span>Reabilitacija</span>
            </a>
        </li>
        @auth
        @if(in_array(Auth::user()->role, ['admin', 'doctor']))
        <li class="nav-item">
            <a class="nav-link {{ request()->is('plan/create') ? 'active' : '' }}" href="/plan/create">
                <img src="/img/createlogo.png">
                <span>Kurti planą</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('plan/plan-control') ? 'active' : '' }}" href="/plan/plan-control">
                <img src="/img/searchlogo.png">
                <span>Mano planai</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('plan/all-plans') ? 'active' : '' }}" href="/plan/all-plans">
                <img src="/img/searchbar.png">
                <span>Planų paieška</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('plan/saved-plans') ? 'active' : '' }}" href="/plan/saved-plans">
                <img src="/img/star.png">
                <span>Išsaugoti planai</span>
            </a>
        </li>
        @endif
        @endauth
        <li class="nav-item">
    <a class="nav-link {{ request()->is('user-contacts') ? 'active' : '' }}" href="/user-contacts">
        <img src="/img/contacts.png">
        <span>Kontaktai</span>
    </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->is('messages') ? 'active' : '' }}" href="{{ route('messages.index') }}">
                <img src="/img/chat.png">
                <span>Pokalbiai</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link dropdown-toggle {{ request()->is('system*') ? 'active' : '' }}" id="settings-btn" href="#" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                <img src="/img/settings.png">
                <span>Nustatymai</span>
            </a>
            <div class="collapse" id="collapseExample">
                <ul class="list-unstyled ps-3">
                    <li><a class="nav-link {{ request()->is('system/profile') ? 'active' : '' }}" href="/system/profile">
                        <img src="/img/userprofile.png"> Profilis</a></li>
                    <li><a class="nav-link {{ request()->is('system/change-password') ? 'active' : '' }}" href="/system/change-password">
                        <img src="/img/key.png"> Slaptažodis</a></li>
                    <li><a class="nav-link {{ request()->is('system/account-management') ? 'active' : '' }}" href="/system/account-management">
                        <img src="/img/accountsetting.png"> Paskyra</a></li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->is('logout') ? 'active' : '' }}" href="/logout">
                <img src="/img/logout.png">
                <span>Atsijungti</span>
            </a>
        </li>
    </ul>
</nav>
<script src="{{ asset('js/sidebar.js') }}"></script>
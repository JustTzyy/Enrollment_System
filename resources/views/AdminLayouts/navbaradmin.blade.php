<div class="sticky-top shadow-sm">
    <nav class="navbar ">
        <div class="container-fluid d-flex justify-content-between align-items-center px-3">
            <button id="sidebarToggle" class="btn  d-md-none">
                ☰
            </button>
            <a class="navbar-brand" href="#">
                <img src="{{ asset('/image/UMIANKonek.png') }}" alt="Logo" height="40">
            </a>
            <div class="dropdown">
                <button class="btn adminaccount btn-light dropdown-toggle d-flex align-items-center" type="button"
                    id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png" alt="User Icon" width="30"
                        height="30" class="me-2">
                    <span>{{ Cookie::get('username') }}</span>
                </button>
                <ul class="dropdown-menu admindropdown " aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item " href="{{ route('AdminComponents.setting') }}">Settings</a></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- Bottom border of navbar --}}
    <div class="bgnavborder p-1 "></div>
</div>
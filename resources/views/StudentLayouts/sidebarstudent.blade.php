<aside class="sidebar">
    <ul class="list-unstyled">


        <li>
            <a href="{{ route('StudentComponents.dashboard') }}" class="mb-3 mt-4 dropdownhover sidebar-link"
                data-active="dashboard">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
        </li>


        <li>
            <a href="{{ route('StudentComponents.corecommitments') }}" class="mb-3 mt-4 dropdownhover sidebar-link"
                data-active="corecommitments">
                <i class="bi bi-bank"></i> Core Commitments
            </a>
        </li>

        <li class="sidebar-title">Institutional Management</li>

        <li>
            <a href="{{ route('StudentComponents.class') }}" class="mb-3 mt-4 dropdownhover sidebar-link"
                data-active="class">
                <i class="bi bi-journal-bookmark-fill"></i> Class Schedule
            </a>
        </li>



        <li>
            <a href="{{ route('StudentComponents.spr') }}" class="mb-3 mt-4 dropdownhover sidebar-link"
                data-active="spr">
                <i class="bi bi-bookmark-plus"></i> SPR
            </a>
        </li>



        <li>
            <a href="{{ route('StudentComponents.credential') }}" class="mb-3 mt-4 dropdownhover sidebar-link"
                data-active="credential">
                <i class="bi bi-paperclip"></i> Credentials
            </a>
        </li>
    </ul>
</aside>
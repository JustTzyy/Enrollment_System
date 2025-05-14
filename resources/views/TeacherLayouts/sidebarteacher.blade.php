<aside class="sidebar">
    <ul class="list-unstyled">


        <li>
            <a href="{{ route('TeacherComponents.dashboard') }}" class="mb-3 mt-4 dropdownhover sidebar-link"
                data-active="dashboard">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
        </li>

        <li class="sidebar-title">Institutional Management</li>


        <li>
            <a href="{{ route('TeacherComponents.corecommitments') }}" class="mb-3 mt-4 dropdownhover sidebar-link"
                data-active="corecommitments">
                <i class="bi bi-bank"></i> Core Commitments
            </a>
        </li>

        <li>
            <a href="{{ route('TeacherComponents.specialists') }}" class="mb-3 mt-4 dropdownhover sidebar-link"
                data-active="specialists">
                <i class="bi bi-person-lines-fill"></i> Specialists
            </a>
        </li>

        <li>
            <a href="{{ route('TeacherComponents.class') }}" class="mb-3 mt-4 dropdownhover sidebar-link"
                data-active="class">
                <i class="bi bi-journal-bookmark-fill"></i> Class Schedule
            </a>
        </li>

    </ul>
</aside>
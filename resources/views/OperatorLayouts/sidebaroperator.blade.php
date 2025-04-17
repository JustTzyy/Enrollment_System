<aside class="sidebar">
    <ul class="list-unstyled">
        <li>
            <a href="{{ route('OperatorComponents.dashboard') }}" class="mb-3 mt-4 dropdownhover sidebar-link"
                data-active="dashboard">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
        </li>
        <li class="sidebar-title">Institutional Management</li>

        <li>
            <div class="dropdown">
                <a class="d-flex dropdownhover justify-content-between align-items-center dropdown-toggle sidebar-link"
                    href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"
                    data-active="transaction">
                    <span><i class="fa-solid fa-users-gear"></i> Transaction</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item sidebar-sublink" href="{{ route('OperatorComponents.enrollment') }}"
                            data-parent="transaction" data-active="enrollment">Enrollment</a></li>
                    <li><a class="dropdown-item sidebar-sublink" href="{{ route('OperatorComponents.schedule') }}"
                            data-parent="transaction" data-active="schedule">Schedule</a></li>
                </ul>
            </div>
        </li>

        <li>
            <div class="dropdown">
                <a class="d-flex dropdownhover justify-content-between align-items-center dropdown-toggle sidebar-link"
                    href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-active="report">
                    <span><i class="fa-solid fa-sliders"></i> Report History</span>
                </a>
                <ul class="dropdown-menu">

                    <li><a class="dropdown-item sidebar-sublink"
                            href="{{ route('OperatorComponents.enrollmenthistory') }}" data-parent="history"
                            data-active="enrollmenthistory">Enrollment History</a></li>
                    <li><a class="dropdown-item sidebar-sublink" href="{{ route('OperatorComponents.studenthistory') }}"
                            data-parent="report" data-active="studenthistory">Student History</a></li>
                    <li><a class="dropdown-item sidebar-sublink" href="{{ route('OperatorComponents.archive') }}"
                            data-parent="report" data-active="archive">Archive</a></li>
                    <li><a class="dropdown-item sidebar-sublink" href="{{ route('OperatorComponents.requirement') }}"
                            data-parent="report" data-active="requirment">Requirement</a></li>

                </ul>
            </div>
        </li>

    </ul>
</aside>

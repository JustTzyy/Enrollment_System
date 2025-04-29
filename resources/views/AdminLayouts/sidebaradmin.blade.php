<aside class="sidebar">
    <ul class="list-unstyled">
        <li>
            <a href="{{ route('AdminComponents.dashboard') }}" class="mb-3 mt-4 dropdownhover sidebar-link"
                data-active="dashboard">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
        </li>
        <li class="sidebar-title">Institutional Management</li>

        <li>
            <div class="dropdown">
                <a class="d-flex dropdownhover justify-content-between align-items-center dropdown-toggle sidebar-link"
                    href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-active="staff">
                    <span><i class="fa-solid fa-users-gear"></i> Staff Management</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item sidebar-sublink" href="{{ route('AdminComponents.admin') }}"
                            data-parent="staff" data-active="admin">Admin</a></li>
                    <li><a class="dropdown-item sidebar-sublink" href="{{ route('AdminComponents.operator') }}"
                            data-parent="staff" data-active="operator">Operator</a></li>
                    <li><a class="dropdown-item sidebar-sublink" href="{{ route('AdminComponents.student') }}"
                            data-parent="staff" data-active="student">Student</a></li>
                    <li><a class="dropdown-item sidebar-sublink" href="{{ route('AdminComponents.teacher') }}"
                            data-parent="staff" data-active="teacher">Teacher</a></li>
                </ul>
            </div>
        </li>

        <li>
            <div class="dropdown">
                <a class="d-flex dropdownhover justify-content-between align-items-center dropdown-toggle sidebar-link"
                    href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-active="transaction">
                    <span><i class="fa-solid fa-sliders"></i> Transaction</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item sidebar-sublink" href="{{ route('AdminComponents.enrollment') }}"
                            data-parent="transaction" data-active="Enrollment">Enrollment</a></li>
                    <li><a class="dropdown-item sidebar-sublink" href="{{ route('AdminComponents.schedule') }}"
                            data-parent="transaction" data-active="Schedule">Schedule</a></li>
                </ul>
            </div>
        </li>

        <li>
            <div class="dropdown">
                <a class="d-flex dropdownhover justify-content-between align-items-center dropdown-toggle sidebar-link"
                    href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-active="curriculum">
                    <span><i class="fa-solid fa-sliders"></i> Year & Curriculum</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item sidebar-sublink" href="{{ route('AdminComponents.teacherassignment') }}"
                            data-parent="curriculum" data-active="teacherassignment">Assignment</a></li>
                    <li><a class="dropdown-item sidebar-sublink" href="{{ route('AdminComponents.strand') }}"
                            data-parent="curriculum" data-active="strand">Strand</a></li>
                    <li><a class="dropdown-item sidebar-sublink" href="{{ route('AdminComponents.section') }}"
                            data-parent="curriculum" data-active="section">Section</a></li>
                    <li><a class="dropdown-item sidebar-sublink" href="{{ route('AdminComponents.subject') }}"
                            data-parent="curriculum" data-active="subject">Subject</a></li>
                            <li><a class="dropdown-item sidebar-sublink" href="{{ route('AdminComponents.subjectassignment') }}"
                            data-parent="curriculum" data-active="subjectassignment">Subject Assignment</a></li>
                </ul>
            </div>
        </li>

        <li class="sidebar-title">History Section</li>

        <li>
            <div class="dropdown">
                <a class="d-flex dropdownhover justify-content-between align-items-center dropdown-toggle sidebar-link"
                    href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-active="history">
                    <span><i class="fa-solid fa-sliders"></i> Report History</span>
                </a>
                <ul class="dropdown-menu">

                    <li><a class="dropdown-item sidebar-sublink" href="{{ route('AdminComponents.adminhistory') }}"
                            data-parent="history" data-active="adminhistory">Admin History</a></li>
                    <li><a class="dropdown-item sidebar-sublink" href="{{ route('AdminComponents.operatorhistory') }}"
                            data-parent="history" data-active="operatorhistory">Operator History</a></li>
                    <li><a class="dropdown-item sidebar-sublink" href="{{ route('AdminComponents.studenthistory') }}"
                            data-parent="history" data-active="studenthistory">Student History</a></li>
                    <li><a class="dropdown-item sidebar-sublink" href="{{ route('AdminComponents.teacherhistory') }}"
                            data-parent="history" data-active="teacherhistory">Teacher History</a></li>


                </ul>
            </div>
        </li>
        <li>
            <a class="gap-2 dropdownhover sidebar-link" href="{{ route('AdminComponents.requirement') }}"
                data-active="enrollment"><i class="bi bi-book"></i> Enrollment History</a>
        </li>

        <li>
            <a class="gap-2 dropdownhover sidebar-link" href="{{ route('AdminComponents.archive') }}"
                data-active="archive"><i class="fa-solid fa-archive"></i> Archives</a>
        </li>
        <li>
            <a class="gap-2 dropdownhover sidebar-link" href="{{ route('AdminComponents.requirement') }}"
                data-active="requirements"><i class="fas fa-file-alt"></i> Requirements</a>
        </li>
    </ul>
</aside>
@extends('Dashboard.student')

@section('title', 'UMIANKonek | Student Dashboard')

@section('content')


{{-- SEARCH --}}

<div class="mb-4">
    <form action="#" method="GET">
        <div class="d-flex align-items-center">
            <input type="text" name="search" class="search-input" placeholder="Search subjects...">
            <i class="fas fa-search search-icon"></i>
        </div>
    </form>
</div>


<!-- Subject Cards Container -->
<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
    @foreach ($subjects as $index => $subject)
    <div class="col">
        <div class="card h-100 subject-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">{{ $subject->subject_name }}</h5>
                <span class="badge bg-primary">{{ $subject->status }}</span>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <i class="fas fa-user-tie me-2"></i>
                    <strong>Teacher:</strong> {{ $subject->teacher_name }}
                </div>
                <div class="mb-3">
                    <i class="fas fa-clock me-2"></i>
                    <strong>Schedule:</strong> {{ $subject->schedule }}
                </div>
                <div class="mb-3">
                    <i class="fas fa-map-marker-alt me-2"></i>
                    <strong>Room:</strong> {{ $subject->room }}
                </div>
                <div class="text-center mt-4">
                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#subjectDetailsModal{{ $index }}">
                        View Details
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Subject Details Modal -->
    <div class="modal fade" id="subjectDetailsModal{{ $index }}" tabindex="-1" aria-labelledby="subjectDetailsLabel{{ $index }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="subjectDetailsLabel{{ $index }}">{{ $subject->subject_name }} - Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Tabs -->
                    <ul class="nav nav-tabs mb-3" id="detailsTab{{ $index }}" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="subject-info-tab{{ $index }}" data-bs-toggle="tab" data-bs-target="#subjectInfo{{ $index }}" type="button" role="tab">Subject Info</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="student-roster-tab{{ $index }}" data-bs-toggle="tab" data-bs-target="#studentRoster{{ $index }}" type="button" role="tab">Student Roster</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="syllabus-tab{{ $index }}" data-bs-toggle="tab" data-bs-target="#syllabus{{ $index }}" type="button" role="tab">Syllabus</button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <!-- Subject Info Tab -->
                        <div class="tab-pane fade show active" id="subjectInfo{{ $index }}" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <dl class="row">
                                        <dt class="col-sm-4">Subject Code:</dt>
                                        <dd class="col-sm-8">{{ $subject->code ?? 'N/A' }}</dd>

                                        <dt class="col-sm-4">Subject Name:</dt>
                                        <dd class="col-sm-8">{{ $subject->subject_name }}</dd>

                                        <dt class="col-sm-4">Teacher:</dt>
                                        <dd class="col-sm-8"> {{ $subject->teacher_name }}</dd>

                                        <dt class="col-sm-4">Department:</dt>
                                        <dd class="col-sm-8">TBD</dd>
                                    </dl>
                                </div>
                                <div class="col-md-6">
                                    <dl class="row">
                                        <dt class="col-sm-4">Schedule:</dt>
                                        <dd class="col-sm-8">{{ $subject->schedule }}</dd>

                                        <dt class="col-sm-4">Room:</dt>
                                        <dd class="col-sm-8">{{ $subject->room }}</dd>

                                        <dt class="col-sm-4">Credits:</dt>
                                        <dd class="col-sm-8">3</dd>

                                        <dt class="col-sm-4">Status:</dt>
                                        <dd class="col-sm-8"><span class="badge bg-primary">{{ $subject->status }}</span></dd>
                                    </dl>
                                </div>
                            </div>
                            <div class="mt-3">
                                <h6>Description:</h6>
                                <p>Placeholder description for {{ $subject->subject_name }}. Replace this with actual course details.</p>
                            </div>
                        </div>
                        <!-- Student Roster Tab -->
                        <div class="tab-pane fade" id="studentRoster{{ $index }}" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Student ID</th>
                                            <th>Name</th>
                                            <th>Year</th>
                                            <th>Email</th>
                                        </tr>
                                    </thead>
                                    <tbody>


                                    <tbody>
                                        @forelse ($classmates as $student)
                                        <tr>
                                            <td>{{ $student->student_id }}</td>
                                            <td>{{ $student->name }}</td>
                                            <td>{{ $student->year }}</td>
                                            <td>{{ $student->email }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No students found in this section.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>

                                </table>
                            </div>
                        </div>




                        <!-- Syllabus Tab -->
                        <div class="tab-pane fade" id="syllabus{{ $index }}" role="tabpanel">
                            <h6>Course Syllabus:</h6>
                            <p>Replace with actual syllabus content.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>






<!-- CSS Styling -->
<style>
    /* Card Styling */
    .subject-card {
        transition: transform 0.3s, box-shadow 0.3s;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.125);
    }

    .subject-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .subject-card .card-header {
        background-color: #b02532;
        border-bottom: 1px solid rgba(0, 0, 0, 0.125);
        padding: 0.75rem 1.25rem;
    }

    .subject-card .card-footer {
        background-color: #f8f9fa;
        border-top: 1px solid rgba(0, 0, 0, 0.125);
        padding: 0.75rem 1.25rem;
    }

    /* Search Box Styling */
    .search-container {
        position: relative;
        max-width: 300px;
    }

    .search-input {
        border-radius: 20px;
        padding: 0.375rem 2rem 0.375rem 1rem;
        border: 1px solid #ced4da;
    }

    .search-icon {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }

    /* Button Styling */
    .btncustom {
        background-color: #4e73df;
        color: white;
        border-radius: 5px;
        font-weight: 500;
    }

    .btncustom:hover {
        background-color: #2e59d9;
        color: white;
    }

    .btnarchive {
        background-color: #858796;
        color: white;
        border-radius: 5px;
        font-weight: 500;
    }

    .btnarchive:hover {
        background-color: #717384;
        color: white;
    }

    /* Modal Tab Styling */
    .nav-tabs .nav-link {
        color: #4e73df;
    }

    .nav-tabs .nav-link.active {
        color: #495057;
        background-color: #fff;
        border-color: #dee2e6 #dee2e6 #fff;
        font-weight: bold;
    }

    /* Modal Styling */
    .modal-header {
        background-color: #b02532 !important;
        border-radius: 5px 5px 0 0;
    }

    /* Badge Styling */
    .badge.bg-primary {
        background-color: #b02532 !important;
    }

    /* Custom Alert Styling */
    .alert {
        border-radius: 5px;
        margin-bottom: 20px;
    }
</style>

<!-- JavaScript for the UI -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Example: Show success message when a new subject is added
        document.getElementById('addSubjectForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const successAlert = document.querySelector('.alert-success');
            successAlert.style.display = 'block';
            successAlert.querySelector('.alert-message').textContent = 'Subject added successfully!';

            // Hide the modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('addSubjectModal'));
            modal.hide();

            // Auto-hide the alert after 3 seconds
            setTimeout(function() {
                successAlert.style.display = 'none';
            }, 3000);
        });

        // Example: Handle search functionality
        const searchInput = document.querySelector('.search-input');
        searchInput?.addEventListener('input', function() {
            const searchText = this.value.toLowerCase();
            const cards = document.querySelectorAll('.subject-card');

            cards.forEach(card => {
                const title = card.querySelector('.card-title').textContent.toLowerCase();
                const teacher = card.querySelector('.card-body').textContent.toLowerCase();

                if (title.includes(searchText) || teacher.includes(searchText)) {
                    card.closest('.col').style.display = '';
                } else {
                    card.closest('.col').style.display = 'none';
                }
            });
        });
    });
</script>

@endsection
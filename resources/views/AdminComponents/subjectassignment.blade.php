@extends('Dashboard.admin')

@section('title', 'UMIANKonek | Admin')

@section('content')
    <header class="text-center mb-2">
        <h1>YEAR & CURRICULUM</h1>
        <p>SUBJECT ASSIGNMENTS</p>
    </header>

    {{-- Success/Error Alerts --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Search & Add --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="search-container">
            <form action="{{ route('AdminComponents.subjectassignment') }}" method="GET">
                <div class="d-flex align-items-center">
                    <input type="text" name="search" class="search-input" placeholder="Search..."
                        value="{{ request('search') }}">
                    <i class="fas fa-search search-icon"></i>
                </div>
            </form>
        </div>

        <div class="d-flex align-items-end ms-auto">
            <button class="btn btncustom mx-2" data-bs-toggle="modal" data-bs-target="#addAssignmentModal">
                New Assignment
            </button>
            <button type="button" class="btn btnarchive mx-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Archived
            </button>
        </div>
    </div>

    {{-- Table --}}
    <div class="table-responsive mt-3">
        <table class="table custom-table">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Subject</th>
                    <th>Strand</th>
                    <th>Time</th>
                    <th>Semester</th>
                    <th>Grade Level</th>
                    <th>Date</th>

                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($assignments as $assignment)
                    <tr>
                        <td>{{ $assignment->code }}</td>                           
                        <td> {{ $assignment->subject->subject }}</td>
                        <td>{{ $assignment->strand->strand }}</td>
                        <td>{{ $assignment->time }}</td>                           
                        <td>{{ $assignment->semester }}</td>                           
                        <td>{{ $assignment->gradeLevel }}</td>                           

                        <td>{{ $assignment->created_at }}</td>
                        <td class="d-flex justify-content-center gap-2">
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#editAssignmentModal{{ $assignment->id }}">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <form action="{{ route('subjectassignment.destroy', $assignment->id) }}" method="POST"
                                onsubmit="return confirm('Delete this assignment?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td >No subject assignments available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @foreach($assignments as $assignment)
            <div class="modal fade" id="editAssignmentModal{{ $assignment->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <form action="{{ route('subjectassignment.update', $assignment->id) }}" method="POST" class="modal-content">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Assignment</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-start">
                            <label class="form-label" for="subject{{ $assignment->id }}">Choose or enter a subject:</label>
                            <input list="subjects{{ $assignment->id }}" id="subject{{ $assignment->id }}" name="subjectID"
                                class="form-control mb-2" placeholder="Start typing subject..."
                                value="{{ $assignment->subjectID }}" required>
                            <datalist id="subjects{{ $assignment->id }}">
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">
                                        {{ $subject->subject }}
                                    </option>
                                @endforeach
                            </datalist>

                            <label class="form-label" for="strand{{ $assignment->id }}">Choose or enter a strand:</label>
                            <input list="strands{{ $assignment->id }}" id="strand{{ $assignment->id }}" name="strandID"
                                class="form-control mb-2" placeholder="Start typing strand name..."
                                value="{{ $assignment->strandID }}" required>
                            <datalist id="strands{{ $assignment->id }}">
                                @foreach($strands as $strand)
                                    <option value="{{ $strand->id }}">
                                        {{ $strand->strand }}
                                    </option>
                                @endforeach
                            </datalist>

                            <!-- Time Limit Dropdown -->
                            <label for="timeLimit{{ $assignment->id }}" class="form-label">Select Time Limit:</label>
                            <select id="timeLimit{{ $assignment->id }}" name="timeLimit" class="form-control mb-2" required>
                                <option value="30" {{ $assignment->timeLimit == 30 ? 'selected' : '' }}>30 Minutes</option>
                                <option value="60" {{ $assignment->timeLimit == 60 ? 'selected' : '' }}>1 Hour</option>
                                <option value="90" {{ $assignment->timeLimit == 90 ? 'selected' : '' }}>1.5 Hours</option>
                                <option value="120" {{ $assignment->timeLimit == 120 ? 'selected' : '' }}>2 Hours</option>
                            </select>

                            <!-- Grade Level Dropdown -->
                            <label for="gradeLevel{{ $assignment->id }}" class="form-label">Select Grade Level:</label>
                            <select id="gradeLevel{{ $assignment->id }}" name="gradeLevel" class="form-control mb-2" required>
                                <option value="Grade 11" {{ $assignment->gradeLevel == 'Grade 11' ? 'selected' : '' }}>Grade 11</option>
                                <option value="Grade 12" {{ $assignment->gradeLevel == 'Grade 12' ? 'selected' : '' }}>Grade 12</option>
                            </select>

                            <!-- Semester Dropdown -->
                            <label for="semester{{ $assignment->id }}" class="form-label">Select Semester:</label>
                            <select id="semester{{ $assignment->id }}" name="semester" class="form-control mb-2" required>
                                <option value="Sem 1" {{ $assignment->semester == 'Sem 1' ? 'selected' : '' }}>Sem 1</option>
                                <option value="Sem 2" {{ $assignment->semester == 'Sem 2' ? 'selected' : '' }}>Sem 2</option>
                            </select>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary w-100">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach

    <nav aria-label="Page navigation example">
        <ul class="pagination">
            @if ($assignments->onFirstPage())
                <li class="page-item disabled"><span class="page-link">Previous</span></li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $assignments->previousPageUrl() }}">Previous</a>
                </li>
            @endif

            @foreach ($assignments->links()->elements[0] as $page => $url)
                <li class="page-item {{ $assignments->currentPage() == $page ? 'active' : '' }}">
                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                </li>
            @endforeach

            @if ($assignments->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $assignments->nextPageUrl() }}">Next</a>
                </li>
            @else
                <li class="page-item disabled"><span class="page-link">Next</span></li>
            @endif
        </ul>
    </nav>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addAssignmentModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('AdminComponents.subjectassignment') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">New Subject Assignment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-start">

                    <label class="form-label" for="subject">Choose or enter a subject:</label>
                    <input list="subjects" id="subject" name="subjectID" class="form-control mb-2"
                        placeholder="Start typing subject..." required>
                    <datalist id="subjects">
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">
                                {{ $subject->subject }}
                            </option>
                        @endforeach
                    </datalist>

                    <label class="form-label" for="strand">Choose or enter a strand:</label>
                    <input list="strands" id="strand" name="strandID" class="form-control mb-2"
                        placeholder="Start typing strand name..." required>
                    <datalist id="strands">
                        @foreach($strands as $strand)
                            <option value="{{ $strand->id }}">
                                {{ $strand->strand }}
                            </option>
                        @endforeach
                    </datalist>

                    <!-- Time Limit Dropdown -->
                    <label for="timeLimit" class="form-label">Select Time Limit:</label>
                    <select id="timeLimit" name="timeLimit" class="form-control mb-2" required>
                        <option value="30">30 Minutes</option>
                        <option value="60">1 Hour</option>
                        <option value="90">1.5 Hours</option>
                        <option value="120">2 Hours</option>
                    </select>

                    <!-- Grade Level Dropdown -->
                    <label for="gradeLevel" class="form-label">Select Grade Level:</label>
                    <select id="gradeLevel" name="gradeLevel" class="form-control mb-2" required>
                        <option value="Grade 11">Grade 11</option>
                        <option value="Grade 12">Grade 12</option>
                    </select>

                    <!-- Semester Dropdown -->
                    <label for="semester" class="form-label">Select Semester:</label>
                    <select id="semester" name="semester" class="form-control mb-2" required>
                        <option value="Sem 1">Sem 1</option>
                        <option value="Sem 2">Sem 2</option>
                    </select>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success w-100">Assign</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@extends('Dashboard.admin')

@section('title', 'UMIANKonek | Admin')

@section('content')
    <header class="text-center mb-2">
        <h1>YEAR & CURRICULUM</h1>
        <p>TEACHER ASSIGNMENTS</p>
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
            <form action="{{ route('AdminComponents.teacherassignment') }}" method="GET">
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
                    <th>Teacher</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($assignments as $assignment)
                    <tr>
                        <td>{{ $assignment->code }}</td>
                        <td>{{ $assignment->subject->subject }}</td>
                        <td>{{ $assignment->user->firstName }} {{ $assignment->user->middleName }}
                            {{ $assignment->user->lastName }}
                        </td>
                        <td>{{ $assignment->created_at }}</td>
                        <td class="d-flex justify-content-center gap-2">
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#editAssignmentModal{{ $assignment->id }}">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <form action="{{ route('teacherassignment.destroy', $assignment->id) }}" method="POST"
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
                        <td colspan="6">No teacher assignments available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @foreach($assignments as $assignment)
            <div class="modal fade" id="editAssignmentModal{{ $assignment->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <form action="{{ route('teacherassignment.update', $assignment->id) }}" method="POST" class="modal-content">
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

                            <label class="form-label" for="teacher{{ $assignment->id }}">Choose or enter a teacher:</label>
                            <input list="teachers{{ $assignment->id }}" id="teacher{{ $assignment->id }}" name="userID"
                                class="form-control mb-2" placeholder="Start typing teacher name..."
                                value="{{ $assignment->userID }}" required>
                            <datalist id="teachers{{ $assignment->id }}">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->firstName }} {{ $user->middleName }} {{ $user->lastName }}
                                    </option>
                                @endforeach
                            </datalist>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary w-100">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach

        <!-- Hereee -->
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
            <form action="{{ route('AdminComponents.teacherassignment') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">New Teacher Assignment</h5>
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

                    <label class="form-label" for="teacher">Choose or enter a teacher:</label>
                    <input list="teachers" id="teacher" name="userID" class="form-control mb-2"
                        placeholder="Start typing teacher name..." required>
                    <datalist id="teachers">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->firstName }} {{ $user->middleName }} {{ $user->lastName }}
                            </option>
                        @endforeach
                    </datalist>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success w-100">Assign</button>
                </div>
            </form>
        </div>
    </div>



@endsection
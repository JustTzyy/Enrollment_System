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
                    <th>Title</th>
                    <th>Time</th>
                    <th>Subject</th>
                    <th>Teacher</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($assignments as $assignment)
                    <tr>
                        <td>{{ $assignment->id }}</td>
                        <td>{{ $assignment->title }}</td>
                        <td>{{ $assignment->time }}</td>
                        <td>{{ $assignment->subject->subject }}</td>
                        <td>{{ $assignment->user->firstName }} {{ $assignment->user->middleName }}
                            {{ $assignment->user->lastName }}</td>
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
                            <label class="form-label">Title:</label>
                            <input type="text" name="title" class="form-control mb-2" value="{{ $assignment->title }}" required>

                            <label class="form-label">Time:</label>
                            <input type="text" name="time" class="form-control mb-2" value="{{ $assignment->time }}" required>

                            <label class="form-label">Subject:</label>
                            <select name="subjectID" class="form-control mb-2" required>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ $assignment->subjectID == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->subject }}
                                    </option>
                                @endforeach
                            </select>

                            <label class="form-label">Assign Teacher:</label>
                            <select name="userID" class="form-control" required>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $assignment->userID == $user->id ? 'selected' : '' }}>
                                        {{ $user->firstName }} {{ $user->middleName }} {{ $user->lastName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary w-100">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach


        {{-- Pagination --}}
        {{ $assignments->links() }}
    </div>

    {{-- Add Modal --}}
    <div class="modal fade" id="addAssignmentModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('AdminComponents.teacherassignment') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">New Teacher Assignment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-start">
                    <label class="form-label">Title:</label>
                    <input type="text" name="title" class="form-control mb-2" placeholder="Enter assignment title" required>

                    <label class="form-label">Time:</label>
                    <input type="text" name="time" class="form-control mb-2" placeholder="Enter time" required>

                    <label class="form-label">Subject:</label>
                    <select name="subjectID" class="form-control mb-2 select2" required>
                        <option disabled selected>Select Subject</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->subject }}</option>
                        @endforeach
                    </select>

                    <label class="form-label">Assign Teacher:</label>
                    <select name="userID" class="form-control mb-2 select2" required>
                        <option disabled selected>Select Teacher</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->firstName }} {{ $user->middleName }} {{ $user->lastName }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success w-100">Assign</button>
                </div>
            </form>
        </div>
    </div>



@endsection
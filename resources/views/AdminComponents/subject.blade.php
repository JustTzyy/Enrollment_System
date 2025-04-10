@extends('Dashboard.admin')

@section('title', 'UMIANKonek | Admin')

@section('content')
    <header class="text-center mb-2">
        <h1>YEAR & CURRICULUM</h1>
        <p>SUBJECTS</p>
    </header>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- SEARCH/ADD --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <!-- Search Input -->
        <div class="search-container">
            <form action="{{ route('AdminComponents.subject') }}" method="GET">
                <div class="d-flex align-items-center">
                    <input type="text" name="search" class="search-input" placeholder="Search..."
                        value="{{ request('search') }}">
                    <i class="fas fa-search search-icon"></i>
                </div>
            </form>
        </div>

        <!-- Button Group Aligned to End -->
        <div class="d-flex align-items-end ms-auto">
            <button type="button" class="btn btncustom mx-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Add Subject
            </button>
            <button type="button" class="btn btnarchive mx-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Archived
            </button>
        </div>
    </div>

    <div class="table-responsive ">
        <table class="table custom-table mt-2">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Subject Name</th>
                    <th scope="col">Type</th>
                    <th scope="col">Description</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($subjects as $subject)

                    <tr>
                        <th scope="row">{{ $subject->id }}</th>
                        <td>{{ $subject->subject }}</td>
                        <td>{{ $subject->type}} </td>
                        <td><button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                        data-bs-target="#userDetailsModal{{ $subject->id }}">See More</button> </td>
                        <td class="d-flex justify-content-center gap-2">

                            <button class="btn btn-primary btn-sm" onclick="if(confirm('Are you sure you want to edit this subject?')) { 
                                                              var myModal = new bootstrap.Modal(document.getElementById('updateSubjectModal{{ $subject->id }}')); 
                                                              myModal.show(); 
                                                                 }">
                                <i class="fa-solid fa-circle-info"></i>
                            </button>

                            <form action="{{ route('subject.destroy', $subject->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this subject?');"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fa-solid fa-box-archive"></i>
                                </button>
                            </form>

                        </td>

                         <!-- Modal -->
                         <div class="modal fade" id="userDetailsModal{{ $subject->id }}" tabindex="-1"
                            aria-labelledby="userDetailsModalLabel{{ $subject->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="userDetailsModalLabel{{ $subject->id }}">Subject Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                    <dt class="col-sm-4">subject Name:</dt>
                                    <dd class="col-sm-8">{{ $subject->subject}}</dd>

                                    <dt class="col-sm-4">subject Type:</dt>
                                    <dd class="col-sm-8">{{ $subject->type}}</dd>

                                    <dt class="col-sm-4">subject description:</dt>
                                    <dd class="col-sm-8">{{ $subject->description}}</dd>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- UPDATE MODAL -->
                        <div class="modal text-start fade" id="updateSubjectModal{{ $subject->id }}" tabindex="-1"
                            aria-labelledby="updateSubjectLabel{{ $subject->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="updateSubjectLabel{{ $subject->id }}">UPDATE SUBJECT
                                        </h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('subject.update', $subject->id) }}" method="POST">
                                            @csrf
                                            <div class="mb-3 labelmodal">
                                                <label for="subjectname{{ $subject->id }}" class="form-label">Subject
                                                    Name:</label>
                                                <input type="text" class="form-control" id="subjectname{{ $subject->id }}"
                                                    name="subject" value="{{ $subject->subject }}" required>
                                            </div>

                                            <div class="mb-3 labelmodal">
                                                <label for="type{{ $subject->id }}" class="form-label">Subject Type:</label>
                                                <select class="form-control" id="type{{ $subject->id }}" name="type" required>
                                                    <option value="minor" {{ $subject->type == 'minor' ? 'selected' : '' }}>Minor
                                                    </option>
                                                    <option value="major" {{ $subject->type == 'major' ? 'selected' : '' }}>Major
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="mb-3 labelmodal">
                                                <label for="subjectdescription{{ $subject->id }}" class="form-label">Subject
                                                    Description:</label>
                                                <textarea class="form-control" id="subjectdescription{{ $subject->id }}"
                                                    name="description" rows="3" required>{{ $subject->description }}</textarea>
                                            </div>

                                            <button type="submit" class="btn btn-primary w-100 mt-3">Update</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                @empty
                    <tr>
                        <td colspan="5">No Subject available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                @if ($subjects->onFirstPage())
                    <li class="page-item disabled"><span class="page-link">Previous</span></li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $subjects->previousPageUrl() }}">Previous</a>
                    </li>
                @endif

                @foreach ($subjects->links()->elements[0] as $page => $url)
                    <li class="page-item {{ $subjects->currentPage() == $page ? 'active' : '' }}">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                @endforeach

                @if ($subjects->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $subjects->nextPageUrl() }}">Next</a>
                    </li>
                @else
                    <li class="page-item disabled"><span class="page-link">Next</span></li>
                @endif
            </ul>
        </nav>
    </div>

    {{-- MODAL --}}
    <div class="modal text-start fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">ADD NEW SUBJECT</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('AdminComponents.subject') }}" method="POST">
                        @csrf
                        <div class="mb-3 labelmodal">
                            <label for="subjectname" class="form-label ">Subject Name:</label>
                            <input type="text" class="form-control" id="subjectname" name="subject"
                                placeholder="Enter subject name">
                        </div>

                        <div class="mb-3 labelmodal">
                            <label for="type" class="form-label">Subject Type:</label>
                            <select class="form-control" id="type" name="type" required>
                                <option value="minor">Minor</option>
                                <option value="major">Major</option>
                            </select>
                        </div>

                        <div class="mb-3 labelmodal">
                            <label for="subjectdescription" class="form-label">Subject Description:</label>
                            <textarea class="form-control" id="subjectdescription" name="description" rows="3"></textarea>
                        </div>

                        <button type="submit" class="btn btn-success w-100 mt-3">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@extends('Dashboard.admin')

@section('title', 'UMIANKonek | Admin')

@section('content')
    <header class="text-center mb-2">
        <h1>YEAR & CURRICULUM</h1>
        <p>SECTIONS</p>
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
            <form action="{{ route('AdminComponents.section') }}" method="GET">
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
                Add Section
            </button>
            <button type="button" class="btn btnarchive mx-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Archived
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table custom-table mt-2">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Section Name</th>
                    <th scope="col">Room</th>
                    <th scope="col">Strand</th>
                    <th scope="col">Description</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sections as $section)
                    <tr>
                        <th scope="row">{{ $section->id }}</th>
                        <td>{{ $section->section }}</td>
                        <td>{{ $section->room }}</td>
                        <td>{{ $section->strand->strand }}</td>
                        <td>
                            <button type="button" class="btn btn-outline-info btn-sm " data-bs-toggle="modal" data-bs-target="#sectionDetailsModal{{ $section->id }}">
                                <i class="fas fa-eye me-1"></i> See More
                            </button>
                        </td>
                        <td class="d-flex justify-content-center gap-2">
                            <button class="btn btn-outline-primary btn-sm d-flex align-items-center" onclick="if(confirm('Are you sure you want to edit this section?')) { var myModal = new bootstrap.Modal(document.getElementById('updateSectionModal{{ $section->id }}')); myModal.show(); }">
                                <i class="fas fa-pen me-1"></i> Edit
                            </button>
                            <form action="{{ route('section.destroy', $section->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this section?');" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm d-flex align-items-center">
                                    <i class="fas fa-trash me-1"></i> Delete
                                </button>
                            </form>
                        </td>

                        <!-- Modal -->
                        <div class="modal fade" id="sectionDetailsModal{{ $section->id }}" tabindex="-1"
                            aria-labelledby="sectionDetailsModalLabel{{ $section->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content shadow-lg border-0">
                                    <div class="modal-header bg-info text-white">
                                        <h5 class="modal-title d-flex align-items-center" id="sectionDetailsModalLabel{{ $section->id }}">
                                            <i class="fas fa-info-circle me-2"></i>Section Details
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-start">
                                        <div class="card border-0">
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <h6 class="fw-bold text-info mb-2"><i class="fas fa-book me-2 text-info"></i>Section Name</h6>
                                                    <div class="ps-4 text-justify">{{ $section->section }}</div>
                                                </div>
                                                <div class="mb-3">
                                                    <h6 class="fw-bold text-info mb-2"><i class="fas fa-door-open me-2 text-info"></i>Room</h6>
                                                    <div class="ps-4 text-justify">{{ $section->room }}</div>
                                                </div>
                                                <div class="mb-3">
                                                    <h6 class="fw-bold text-info mb-2"><i class="fas fa-layer-group me-2 text-info"></i>Strand</h6>
                                                    <div class="ps-4 text-justify">{{ $section->strand->strand }}</div>
                                                </div>
                                                <div class="mb-3">
                                                    <h6 class="fw-bold text-info mb-2"><i class="fas fa-align-left me-2 text-info"></i>Description</h6>
                                                    <div class="ps-4 text-justify">{{ $section->description }}</div>
                                                </div>
                                                <div class="mb-3">
                                                    <h6 class="fw-bold text-info mb-2"><i class="fas fa-calendar-alt me-2 text-info"></i>Semester</h6>
                                                    <div class="ps-4 text-justify">{{ $section->semester }}</div>
                                                </div>
                                                <div>
                                                    <h6 class="fw-bold text-info mb-2"><i class="fas fa-graduation-cap me-2 text-info"></i>Grade Level</h6>
                                                    <div class="ps-4 text-justify">{{ $section->gradeLevel }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- UPDATE MODAL -->
                        <div class="modal text-start fade" id="updateSectionModal{{ $section->id }}" tabindex="-1"
                            aria-labelledby="updateSectionLabel{{ $section->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="updateSectionLabel{{ $section->id }}">UPDATE SECTION</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('section.update', $section->id) }}" method="POST">
                                            @csrf
                                            <div class="mb-3 labelmodal">
                                                <label for="sectionname{{ $section->id }}" class="form-label">Section Name:</label>
                                                <input type="text" class="form-control" id="sectionname{{ $section->id }}"
                                                    name="section" value="{{ $section->section }}" required>
                                            </div>
                                            <div class="mb-3 labelmodal">
                                                <label for="sectiondescription{{ $section->id }}" class="form-label">Section Description:</label>
                                                <textarea class="form-control" id="sectiondescription{{ $section->id }}"
                                                    name="description" rows="3" required>{{ $section->description }}</textarea>
                                            </div>

                                            <div class="mb-3 labelmodal">
                                                <label for="room{{ $section->id }}" class="form-label">Room:</label>
                                                <input type="text" class="form-control" id="room{{ $section->id }}" name="room"
                                                    value="{{ $section->room }}" required>
                                            </div>

                                            <div class="mb-3 labelmodal">
                                                <label for="strandID{{ $section->id }}" class="form-label">Strand:</label>
                                                <select class="form-select" id="strandID{{ $section->id }}" name="strandID" required>
                                                    @foreach($strands as $strand)
                                                        <option value="{{ $strand->id }}" 
                                                            {{ $strand->id == $section->strandID ? 'selected' : '' }}>
                                                            {{ $strand->strand }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-3 labelmodal">
    <label for="gradeLevel{{ $section->id }}" class="form-label">Grade Level:</label>
    <select class="form-select" id="gradeLevel{{ $section->id }}" name="gradeLevel" required>
        <option value="Grade 11" {{ $section->gradeLevel == 'Grade 11' ? 'selected' : '' }}>Grade 11</option>
        <option value="Grade 12" {{ $section->gradeLevel == 'Grade 12' ? 'selected' : '' }}>Grade 12</option>
    </select>
</div>

<div class="mb-3 labelmodal">
    <label for="semester{{ $section->id }}" class="form-label">Semester:</label>
    <select class="form-select" id="semester{{ $section->id }}" name="semester" required>
        <option value="Sem 1" {{ $section->semester == 'Sem 1' ? 'selected' : '' }}>1st Semester</option>
        <option value="Sem 2" {{ $section->semester == 'Sem 2' ? 'selected' : '' }}>2nd Semester</option>
    </select>
</div>

                                            <button type="submit" class="btn btn-primary w-100 mt-3">Update</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                @empty
                    <tr>
                        <td colspan="6">No Section available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                @if ($sections->onFirstPage())
                    <li class="page-item disabled"><span class="page-link">Previous</span></li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $sections->previousPageUrl() }}">Previous</a>
                    </li>
                @endif

                @foreach ($sections->links()->elements[0] as $page => $url)
                    <li class="page-item {{ $sections->currentPage() == $page ? 'active' : '' }}">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                @endforeach

                @if ($sections->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $sections->nextPageUrl() }}">Next</a>
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
                    <h1 class="modal-title fs-5" id="exampleModalLabel">ADD NEW SECTION</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('AdminComponents.section') }}" method="POST">
                        @csrf
                        <div class="mb-3 labelmodal">
                            <label for="sectionname" class="form-label">Section Name:</label>
                            <input type="text" class="form-control" id="sectionname" name="section"
                                placeholder="Enter section name">
                        </div>
                        <div class="mb-3 labelmodal">
                            <label for="sectiondescription" class="form-label">Section Description:</label>
                            <textarea class="form-control" id="sectiondescription" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3 labelmodal">
                            <label for="room" class="form-label">Room:</label>
                            <input type="text" class="form-control" id="room" name="room" placeholder="Enter room">
                        </div>
                        <div class="mb-3 labelmodal">
                            <label for="strandID" class="form-label">Strand:</label>
                            <select class="form-select" id="strandID" name="strandID" required>
                                @foreach($strands as $strand)
                                    <option value="{{ $strand->id }}">{{ $strand->strand }}</option>

                                    
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 labelmodal">
    <label for="gradeLevel" class="form-label">Grade Level:</label>
    <select class="form-select" id="gradeLevel" name="gradeLevel" required>
        <option value="" disabled selected>Select grade level</option>
        <option value="Grade 11">Grade 11</option>
        <option value="Grade 12">Grade 12</option>
    </select>
</div>

<div class="mb-3 labelmodal">
    <label for="semester" class="form-label">Semester:</label>
    <select class="form-select" id="semester" name="semester" required>
        <option value="" disabled selected>Select semester</option>
        <option value="Sem 1">1st Semester</option>
        <option value="Sem 2">2nd Semester</option>
    </select>
</div>
                        <button type="submit" class="btn btn-success w-100 mt-3">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

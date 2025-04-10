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
                        <td><button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                data-bs-target="#sectionDetailsModal{{ $section->id }}">See More</button></td>
                        <td class="d-flex justify-content-center gap-2">
                            <button class="btn btn-primary btn-sm" onclick="if(confirm('Are you sure you want to edit this section?')) { 
                                                      var myModal = new bootstrap.Modal(document.getElementById('updateSectionModal{{ $section->id }}')); 
                                                      myModal.show(); 
                                                         }">
                                <i class="fa-solid fa-circle-info"></i>
                            </button>

                            <form action="{{ route('section.destroy', $section->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this section?');"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fa-solid fa-box-archive"></i>
                                </button>
                            </form>
                        </td>

                        <!-- Modal -->
                        <div class="modal fade" id="sectionDetailsModal{{ $section->id }}" tabindex="-1"
                            aria-labelledby="sectionDetailsModalLabel{{ $section->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="sectionDetailsModalLabel{{ $section->id }}">Section Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                    <dt class="col-sm-4">Section Name:</dt>
                                    <dd class="col-sm-8">{{ $section->section}}</dd>

                                    <dt class="col-sm-4">Room:</dt>
                                    <dd class="col-sm-8">{{ $section->room}}</dd>

                                    <dt class="col-sm-4">Strand:</dt>
                                    <dd class="col-sm-8">{{ $section->strand->strand }}</dd>

                                    <dt class="col-sm-4">Section Description:</dt>
                                    <dd class="col-sm-8">{{ $section->description}}</dd>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                        <button type="submit" class="btn btn-success w-100 mt-3">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

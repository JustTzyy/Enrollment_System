@extends('Dashboard.admin')

@section('title', 'UMIANKonek | Admin')

@section('content')
    <header class="text-center mb-2">
        <h1>YEAR & CURRICULUM</h1>
        <p>STRANDS</p>
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
    <div class="d-flex justify-content-between align-items-center  flex-wrap gap-3">
        <!-- Search Input -->
        <div class="search-container">
            <form action="{{ route('AdminComponents.strand') }}" method="GET">
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
         
        </div>
    </div>

    <div class="table-responsive ">
        <table class="table custom-table mt-2">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Strand Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($strands as $strand)

                    <tr>
                        <th scope="row">{{ $strand->id }}</th>
                        <td>{{ $strand->strand }}</td>
                        <td>
                            <button type="button" class="btn btn-outline-info btn-sm   " data-bs-toggle="modal" data-bs-target="#userDetailsModal{{ $strand->id }}">
                                <i class="fas fa-eye me-1"></i> See More
                            </button>
                        </td>
                        <td class="d-flex justify-content-center gap-2">
                            <button class="btn btn-outline-primary btn-sm d-flex align-items-center" onclick="if(confirm('Are you sure you want to edit this strand?')) { var myModal = new bootstrap.Modal(document.getElementById('updateStrandModal{{ $strand->id }}')); myModal.show(); }">
                                <i class="fas fa-pen me-1"></i> Edit
                            </button>
                            <form action="{{ route('strand.destroy', $strand->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this strand?');" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm d-flex align-items-center">
                                    <i class="fas fa-trash me-1"></i> Delete
                                </button>
                            </form>
                        </td>

                      

                        <!-- Modal -->
                        <div class="modal fade" id="userDetailsModal{{ $strand->id }}" tabindex="-1"
                            aria-labelledby="userDetailsModalLabel{{ $strand->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content shadow-lg border-0">
                                    <div class="modal-header bg-info text-white">
                                        <h5 class="modal-title d-flex align-items-center" id="userDetailsModalLabel{{ $strand->id }}">
                                            <i class="fas fa-info-circle me-2"></i>Strand Details
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-start">
                                        <div class="card border-0">
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <h6 class="fw-bold text-info mb-2"><i class="fas fa-book me-2 text-info"></i>Strand Name</h6>
                                                    <div class="ps-4 text-justify">{{ $strand->strand }}</div>
                                                </div>
                                                <div>
                                                    <h6 class="fw-bold text-info mb-2"><i class="fas fa-align-left me-2 text-info"></i>Description</h6>
                                                    <div class="ps-4 text-justify">{{ $strand->description }}</div>
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
                        <div class="modal text-start fade" id="updateStrandModal{{ $strand->id }}" tabindex="-1"
                            aria-labelledby="updateStrandLabel{{ $strand->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content shadow-lg border-0">
                                    <div class="modal-header bg-light">
                                        <h1 class="modal-title fs-5" id="updateStrandLabel{{ $strand->id }}"><i class="fas fa-edit me-2"></i>Update Strand</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('strand.update', $strand->id) }}" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="strandname{{ $strand->id }}" class="form-label fw-bold">Strand Name:</label>
                                                <input type="text" class="form-control rounded-pill" id="strandname{{ $strand->id }}"
                                                    name="strand" value="{{ $strand->strand }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="strandescription{{ $strand->id }}" class="form-label fw-bold">Description:</label>
                                                <textarea class="form-control rounded-3" id="strandescription{{ $strand->id }}"
                                                    name="description" rows="3" required>{{ $strand->description }}</textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 shadow-sm">
                                                <i class="fas fa-save me-2"></i>Update Strand
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                @empty
                    <tr>
                        <td colspan="5">No Strand available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Hereee -->
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                @if ($strands->onFirstPage())
                    <li class="page-item disabled"><span class="page-link">Previous</span></li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $strands->previousPageUrl() }}">Previous</a>
                    </li>
                @endif

                @foreach ($strands->links()->elements[0] as $page => $url)
                    <li class="page-item {{ $strands->currentPage() == $page ? 'active' : '' }}">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                @endforeach

                @if ($strands->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $strands->nextPageUrl() }}">Next</a>
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
                    <h1 class="modal-title fs-5" id="exampleModalLabel">ADD NEW OPERATOR</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('AdminComponents.strand') }}" method="POST">
                        @csrf
                        <div class="mb-3 labelmodal">
                            <label for="firstName" class="form-label ">Strand Name:</label>
                            <input type="text" class="form-control" id="strandname" name="strand"
                                placeholder="Enter strand name">
                        </div>
                        <div class="mb-3 labelmodal">
                            <label for="strandescription" class="form-label">Strand Description:</label>
                            <textarea class="form-control" id="strandescription" name="description" rows="3"></textarea>
                        </div>

                        <button type="submit" class="btn btn-success w-100 mt-3">Save</button>

                </div>
                </form>

            </div>
        </div>
    </div>

@endsection
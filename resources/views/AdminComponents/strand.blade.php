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
                        <td><button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                data-bs-target="#userDetailsModal{{ $strand->id }}">See More</button></td>
                        <td class="d-flex justify-content-center gap-2">

                            <button class="btn btn-primary btn-sm" onclick="if(confirm('Are you sure you want to edit this strand?')) { 
                                                      var myModal = new bootstrap.Modal(document.getElementById('updateStrandModal{{ $strand->id }}')); 
                                                      myModal.show(); 
                                                         }">
                                <i class="fa-solid fa-circle-info"></i>
                            </button>

                            <form action="{{ route('strand.destroy', $strand->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this strand?');"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fa-solid fa-box-archive"></i>
                                </button>
                            </form>

                        </td>

                      

                        <!-- Modal -->
                        <div class="modal fade" id="userDetailsModal{{ $strand->id }}" tabindex="-1"
                            aria-labelledby="userDetailsModalLabel{{ $strand->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="userDetailsModalLabel{{ $strand->id }}">Subject Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                    <dt class="col-sm-4">Strand Name:</dt>
                                    <dd class="col-sm-8">{{ $strand->strand}}</dd>

                                    <dt class="col-sm-4">Strand description:</dt>
                                    <dd class="col-sm-8">{{ $strand->description}}</dd>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- UPDATE MODAL -->
                        <div class="modal text-start fade" id="updateStrandModal{{ $strand->id }}" tabindex="-1"
                            aria-labelledby="updateStrandLabel{{ $strand->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="updateStrandLabel{{ $strand->id }}">UPDATE STRAND</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('strand.update', $strand->id) }}" method="POST">
                                            @csrf
                                            <div class="mb-3 labelmodal">
                                                <label for="strandname{{ $strand->id }}" class="form-label">Strand Name:</label>
                                                <input type="text" class="form-control" id="strandname{{ $strand->id }}"
                                                    name="strand" value="{{ $strand->strand }}" required>
                                            </div>
                                            <div class="mb-3 labelmodal">
                                                <label for="strandescription{{ $strand->id }}" class="form-label">Strand
                                                    Description:</label>
                                                <textarea class="form-control" id="strandescription{{ $strand->id }}"
                                                    name="description" rows="3" required>{{ $strand->description }}</textarea>
                                            </div>

                                            <button type="submit" class="btn btn-primary w-100 mt-3">Update</button>
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
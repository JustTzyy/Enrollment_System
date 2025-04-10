@extends('Dashboard.admin')

@section('title', 'UMIANKonek | Admin')

@section('content')
    <header class="text-center mb-2">
        <h1>YEAR & CURRICULUM</h1>
        <p>SCHOOL YEARS</p>
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
            <form action="{{ route('AdminComponents.schoolyear') }}" method="GET">
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
                Add School Year
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
                    <th scope="col">Year Start</th>
                    <th scope="col">Year End</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($schoolYears as $schoolYear)
                    <tr>
                        <th scope="row">{{ $schoolYear->id }}</th>
                        <td>{{ $schoolYear->yearStart }}</td>
                        <td>{{ $schoolYear->yearEnd }}</td>
                        <td>
                            <form action="{{ route('schoolyear.setActive', $schoolYear->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="btn btn-sm {{ $schoolYear->status === 'active' ? 'btn-success' : 'btn-secondary' }}">
                                    {{ ucfirst($schoolYear->status) }}
                                </button>
                            </form>
                        </td>
                        <td class="d-flex justify-content-center gap-2">
                            <button class="btn btn-primary btn-sm" onclick="if(confirm('Are you sure you want to edit this year?')) { 
                                                        var myModal = new bootstrap.Modal(document.getElementById('updateSchoolYearModal{{ $schoolYear->id }}')); 
                                                        myModal.show(); 
                                                        }">
                                <i class="fa-solid fa-circle-info"></i>
                            </button>

                            <form action="{{ route('schoolyear.destroy', $schoolYear->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this school year?');"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fa-solid fa-box-archive"></i>
                                </button>
                            </form>
                        </td>

                        <!-- UPDATE MODAL -->
                        <div class="modal text-start fade" id="updateSchoolYearModal{{ $schoolYear->id }}" tabindex="-1"
                            aria-labelledby="updateSchoolYearLabel{{ $schoolYear->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="updateSchoolYearLabel{{ $schoolYear->id }}">UPDATE
                                            SCHOOL YEAR</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('schoolyear.update', $schoolYear->id) }}" method="POST">
                                            @csrf
                                            <div class="mb-3 labelmodal">
                                                <label for="yearStart{{ $schoolYear->id }}" class="form-label">Year
                                                    Start:</label>
                                                <input type="number" class="form-control" id="yearStart{{ $schoolYear->id }}"
                                                    name="yearStart" value="{{ $schoolYear->yearStart }}" min="1900" max="2099"
                                                    required>
                                            </div>

                                            <div class="mb-3 labelmodal">
                                                <label for="yearEnd{{ $schoolYear->id }}" class="form-label">Year End:</label>
                                                <input type="number" class="form-control" id="yearEnd{{ $schoolYear->id }}"
                                                    name="yearEnd" value="{{ $schoolYear->yearEnd }}" min="1900" max="2099"
                                                    required>
                                            </div>

                                            <button type="submit" class="btn btn-primary w-100 mt-3">Update</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                @empty
                    <tr>
                        <td colspan="5">No School Year available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                @if ($schoolYears->onFirstPage())
                    <li class="page-item disabled"><span class="page-link">Previous</span></li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $schoolYears->previousPageUrl() }}">Previous</a>
                    </li>
                @endif

                @foreach ($schoolYears->links()->elements[0] as $page => $url)
                    <li class="page-item {{ $schoolYears->currentPage() == $page ? 'active' : '' }}">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                @endforeach

                @if ($schoolYears->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $schoolYears->nextPageUrl() }}">Next</a>
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
                    <h1 class="modal-title fs-5" id="exampleModalLabel">ADD NEW SCHOOL YEAR</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('AdminComponents.schoolyear') }}" method="POST">
                        @csrf
                        <div class="mb-3 labelmodal">
                            <label for="yearStart" class="form-label">Year Start:</label>
                            <input type="number" class="form-control" id="yearStart" name="yearStart"
                                placeholder="Enter year start" min="1900" max="2099">
                        </div>

                        <div class="mb-3 labelmodal">
                            <label for="yearEnd" class="form-label">Year End:</label>
                            <input type="number" class="form-control" id="yearEnd" name="yearEnd"
                                placeholder="Enter year end" min="1900" max="2099">
                        </div>

                        <button type="submit" class="btn btn-success w-100 mt-3">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
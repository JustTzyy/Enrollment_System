@extends('Dashboard.admin')

@section('title', 'UMIANKonek | Admin')

@section('content')
    <header class="text-center mb-2">
        <h1>REPORT HISTORY</h1>
        <p>TEACHER HISTORY</p>
    </header>

    {{-- SEARCH & FILTER --}}
    <div class="d-flex justify-content-between align-items-center mt-5 flex-wrap gap-3">
        <!-- Search Input -->
        <div class="search-container">

        <form action="{{ route('AdminComponents.operatorhistory') }}" method="GET">
      <div class="d-flex align-items-center">
      <input type="text" name="search" class="search-input" placeholder="Search..." value="{{ request('search') }}">
      <i class="fas fa-search search-icon"></i>
      </div>
    </form>
    </div>

        <!-- Status Dropdown -->
        <div class="dropdown">
            <button class="btn btn-outline-info dropdown-toggle d-flex align-items-center shadow-sm rounded-pill px-3 py-2" type="button" data-bs-toggle="dropdown">
                <i class="fas fa-filter me-2"></i> {{ request('status', 'Filter by Status') }}
            </button>
            <ul class="dropdown-menu shadow rounded-3 mt-1">
                <li><a class="dropdown-item py-2" href="{{ route('AdminComponents.teacherhistory', ['status' => 'Added'] + request()->query()) }}"><i class="fas fa-plus-circle text-success me-2"></i>Added</a></li>
                <li><a class="dropdown-item py-2" href="{{ route('AdminComponents.teacherhistory', ['status' => 'Updated'] + request()->query()) }}"><i class="fas fa-edit text-primary me-2"></i>Updated</a></li>
                <li><a class="dropdown-item py-2" href="{{ route('AdminComponents.teacherhistory', ['status' => 'Deleted'] + request()->query()) }}"><i class="fas fa-trash-alt text-danger me-2"></i>Deleted</a></li>
                <li><a class="dropdown-item py-2" href="{{ route('AdminComponents.teacherhistory', array_filter(request()->except('status'))) }}"><i class="fas fa-list text-info me-2"></i>All</a></li>
            </ul>
        </div>
        <style>
        .dropdown-menu {
            min-width: 200px;
            border-radius: 1rem;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        }
        .dropdown-item {
            border-radius: 0.5rem;
            transition: background 0.2s;
        }
        .dropdown-item:hover {
            background: #e9f5fb;
        }
        </style>
    </div>

    {{-- HISTORY TABLE --}}
    <div class="table-responsive mt-2">
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Fullname</th>
                    <th scope="col">Status</th>
                    <th scope="col">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($histories as $history)
                    <tr>
                        <td>{{ $history->id }}</td>
                        <td>{{ $history->user->firstName }}{{ $history->user->middleName  }} {{ $history->user->lastName  }}</td>
                        <td>{{ $history->status }}</td>
                        <td>{{ \Carbon\Carbon::parse($history->created_at)->format('F j, Y, h:i A') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No history records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Hereee -->
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                @if ($histories->onFirstPage())
                    <li class="page-item disabled"><span class="page-link">Previous</span></li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $histories->previousPageUrl() }}">Previous</a>
                    </li>
                @endif

                @foreach ($histories->links()->elements[0] as $page => $url)
                    <li class="page-item {{ $histories->currentPage() == $page ? 'active' : '' }}">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                @endforeach

                @if ($histories->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $histories->nextPageUrl() }}">Next</a>
                    </li>
                @else
                    <li class="page-item disabled"><span class="page-link">Next</span></li>
                @endif
            </ul>
        </nav>


@endsection
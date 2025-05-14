@extends('Dashboard.admin')

@section('title', 'UMIANKonek | Admin')

@section('content')
    <header class="text-center mb-2">
        <h1>STAFF MANAGEMENT</h1>
        <p>REQUIREMENT</p>
    </header>

    {{-- SEARCH/ADD --}}
    <div class="d-flex justify-content-between align-items-center mt-5 flex-wrap gap-3">
        <!-- Search Input -->
        <div class="search-container">
            <form action="{{ route('AdminComponents.requirement') }}" method="GET">
                <div class="d-flex align-items-center">
                    <input type="text" name="search" class="search-input" placeholder="Search..."
                        value="{{ request('search') }}">
                    <i class="fas fa-search search-icon"></i>
                </div>
            </form>
        </div>

        <!-- Button Group Aligned to End -->
        <div class="d-flex align-items-end ms-auto">
            <!-- Date Range Pickers -->
            <div class="d-flex align-items-end justify-content-end gap-2 mx-2"></div>  
        </div>
    </div>

    <div class="table-responsive ">
        <table class="table custom-table mt-2">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">PDS</th>
                    <th scope="col">EF</th>
                    <th scope="col">GM</th>
                    <th scope="col">F137</th>
                    <th scope="col">PSA</th>
                    <th scope="col">GC</th>
                    <th scope="col">NCAE</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <th scope="row">{{ $user->id }}</th>
                        <td>{{ $user->firstName }} {{ $user->lastName }}</td>

                        @foreach($requirements as $requirement)
                            <td class="text-center align-middle">
                                @php
                                    $hasRequirement = $user->requirements->contains('id', $requirement->id);
                                @endphp
                                <div class="form-check d-flex justify-content-center align-items-center mb-0">
                                    <input class="form-check-input {{ $hasRequirement ? 'bg-info border-info' : 'bg-white border-secondary' }}" type="checkbox" disabled value="" id="checkChecked"
                                    {{ $hasRequirement ? 'checked' : '' }} data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $requirement->name }}">
                                </div>
                            </td>
                        @endforeach
                    </tr>
@empty
                    <tr>
      <td colspan="9">No Student available</td>
      </tr>
                @endforelse
            </tbody>
        </table>
        <!-- Hereee -->
    <nav aria-label="Page navigation example">
    <ul class="pagination">
      @if ($users->onFirstPage())
      <li class="page-item disabled"><span class="page-link">Previous</span></li>
    @else
      <li class="page-item">
      <a class="page-link" href="{{ $users->previousPageUrl() }}">Previous</a>
      </li>
    @endif

      @foreach ($users->links()->elements[0] as $page => $url)
      <li class="page-item {{ $users->currentPage() == $page ? 'active' : '' }}">
      <a class="page-link" href="{{ $url }}">{{ $page }}</a>
      </li>
    @endforeach

      @if ($users->hasMorePages())
      <li class="page-item">
      <a class="page-link" href="{{ $users->nextPageUrl() }}">Next</a>
      </li>
    @else
      <li class="page-item disabled"><span class="page-link">Next</span></li>
    @endif
    </ul>
    </nav>

    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
    </script>
@endsection

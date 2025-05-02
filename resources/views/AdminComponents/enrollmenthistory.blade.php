@extends('Dashboard.admin')

@section('title', 'UMIANKonek | Admin')

@section('content')
    <header class="text-center mb-2">
        <h1>REPORT HISTORY</h1>
        <p>ENROLLMENT HISTORY</p>
    </header>

    {{-- SEARCH & FILTER --}}
    <div class="d-flex justify-content-between align-items-center mt-5 flex-wrap gap-3">
        <!-- Search Input -->
        <div class="search-container">
            <form action="{{ route('AdminComponents.enrollmenthistory') }}" method="GET">
                <div class="d-flex align-items-center">
                    <input type="text" name="search" class="search-input" placeholder="Search..." value="{{ request('search') }}">
                    <i class="fas fa-search search-icon"></i>
                </div>
            </form>
        </div>

        <!-- Grade Level Filter -->
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                {{ request('gradeLevel', 'Grade Level') }}
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('AdminComponents.enrollmenthistory', ['gradeLevel' => 'Grade 11'] + request()->except('page')) }}">Grade 11</a></li>
                <li><a class="dropdown-item" href="{{ route('AdminComponents.enrollmenthistory', ['gradeLevel' => 'Grade 12'] + request()->except('page')) }}">Grade 12</a></li>
            </ul>
        </div>

        <!-- Semester Filter -->
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                {{ request('semester', 'Semester') }}
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('AdminComponents.enrollmenthistory', ['semester' => 'Sem 1'] + request()->except('page')) }}">1st Sem</a></li>
                <li><a class="dropdown-item" href="{{ route('AdminComponents.enrollmenthistory', ['semester' => 'Sem 2'] + request()->except('page')) }}">2nd Sem</a></li>
            </ul>
        </div>

        <!-- School Year Filter -->
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                @php
                    $selectedSY = $schoolYears->firstWhere('id', request('schoolYear'));
                @endphp
                {{ $selectedSY ? $selectedSY->yearStart . ' - ' . $selectedSY->yearEnd : 'School Year' }}
            </button>
            <ul class="dropdown-menu">
                @foreach ($schoolYears as $year)
                    <li>
                        <a class="dropdown-item" href="{{ route('AdminComponents.enrollmenthistory', ['schoolYear' => $year->id] + request()->except('page')) }}">
                            {{ $year->yearStart }} - {{ $year->yearEnd }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- ENROLLMENT HISTORY TABLE --}}
    <div class="table-responsive mt-2">
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Fullname</th>
                    <th>Grade Level</th>
                    <th>Semester</th>
                    <th>Strand</th>
                    <th>Section</th>
                    <th>School Year</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($histories as $history)
                    <tr>
                        <td>{{ $history->id }}</td>
                        <td>{{ $history->user->firstName }} {{ $history->user->middleName }} {{ $history->user->lastName }}</td>
                        <td>{{ $history->gradeLevel }}</td>
                        <td>{{ $history->section->semester }}</td>
                        <td>{{ $history->strand->strand ?? 'N/A' }}</td>
                        <td>{{ $history->section->section ?? 'N/A' }}</td>
                        <td>
                            {{ $history->schoolYear->yearStart ?? '' }} - {{ $history->schoolYear->yearEnd ?? '' }}
                        </td>
                        <td>{{ \Carbon\Carbon::parse($history->created_at)->format('F j, Y, h:i A') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No enrollment records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- PAGINATION --}}
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
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
    </div>
@endsection

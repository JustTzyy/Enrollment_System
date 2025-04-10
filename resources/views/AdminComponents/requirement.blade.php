@extends('Dashboard.admin')

@section('title', 'UMIANKonek | Admin')

@section('content')
    <header class="text-center mb-2">
        <h1>STAFF MANAGEMENT</h1>
        <p>OPERATORS</p>
    </header>

    {{-- SEARCH/ADD --}}
    <div class="d-flex justify-content-between align-items-center mt-5 flex-wrap gap-3">
        <!-- Search Input -->
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Search...">
            <i class="fas fa-search search-icon"></i>
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
                    <th scope="col">PSO</th>
                    <th scope="col">GC</th>
                    <th scope="col">NCAE</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <th scope="row">{{ $user->id }}</th>
                        <td>{{ $user->firstName }} {{ $user->lastName }}</td>

                        @foreach($requirements as $requirement)
                            <td>
                                @php
                                    $hasRequirement = $user->requirements->contains('id', $requirement->id);
                                @endphp

                                <input class="form-check-input" type="checkbox" disabled value="" id="checkChecked"
                                {{ $hasRequirement ? 'checked' : '' }}>
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

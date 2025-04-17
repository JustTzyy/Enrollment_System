@extends('Dashboard.operator')

@section('title', 'UMIANKonek | Operator')

@section('operatorcontent')
    <header class="text-center mb-2">
        <h1>REPORT HISTORY</h1>
        <p>ENROLLMENT HISTORY</p>
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
            <div class="d-flex align-items-end justify-content-end gap-2 mx-2">
                <label for="start_date" class="form-label">Search Date:</label>

                <div class="form d-flex justify-content-center align-items-center">
                    <input type="date" class="form-control date" id="dateFrom">
                </div>
                <label class="form-label">to</label>
                <div class="form d-flex justify-content-center align-items-center">
                    <input type="date" class="form-control date" id="dateTo">
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive ">
        <table class="table custom-table mt-2">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Fullname</th>
                    <th scope="col">Email</th>
                    <th scope="col">Added</th>
                    <th scope="col">Edited</th>
                    <th scope="col">Deleted</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">1</th>
                    <td>Disney Princess</td>
                    <td>disneyprincess@gmail.com</td>
                    <td>01/02/2025 11:45pm</td>
                    <td>04/09/2025 02:08pm</td>
                    <td>03/25/2025 03:17pm</td>
                    <td class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn styled-button btn-primary btn-sm">
                            <i class="fa-solid fa-circle-info"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

@endsection

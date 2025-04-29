@extends('Dashboard.admin')

@section('title', 'UMIANKonek | Admin')

@section('content')
    <header>
        <h1 class="d-flex justify-content-center align-text-center ">Welcome, {{ Cookie::get('username') }}!</h1>
        <form method="GET" action="{{ route('AdminComponents.dashboard') }}">
    <div class="d-flex justify-content-center align-items-center">
        <label for="schoolYear" class="me-2">SY:</label>
        <select name="schoolYear" id="schoolYear" class="form-select w-auto" onchange="this.form.submit()">
            @foreach($schoolYears as $year)
                <option value="{{ $year->id }}"
                    @if($activeYear && $activeYear->id == $year->id) selected @endif>
                    {{ $year->yearStart }} - {{ $year->yearEnd }}
                </option>
            @endforeach
        </select>
    </div>
</form>
    </header>
    <div class="dashboard-container">
        <div class="dashboard-cards_box">

            <div class="dashboard-cards_item">
                <a href="#" class="dashboard-cards-item_link">
                    <div class="dashboard-cards-item_bg"></div>

                    <div class="dashboard-cards-item_title">
                        TOTAL STUDENTS
                    </div>

                    <div class="dashboard-cards-item_number-box">
                        <span class="dashboard-cards-item_number">
                            {{ number_format($studentCount) }}
                        </span>
                        <span class="dashboard-cards-item_text">
                            STUDENTS
                        </span>
                    </div>
                </a>
            </div>


            <div class="dashboard-cards_item">
                <a href="#" class="dashboard-cards-item_link">
                    <div class="dashboard-cards-item_bg"></div>

                    <div class="dashboard-cards-item_title">
                        TOTAL ENROLLED
                    </div>

                    <div class="dashboard-cards-item_number-box">
                        <span class="dashboard-cards-item_number ">
                            1,400
                        </span>
                        <span class="dashboard-cards-item_text">
                            STUDENTS
                        </span>
                    </div>
                </a>
            </div>

            <div class="dashboard-cards_item">
                <a href="#" class="dashboard-cards-item_link">
                    <div class="dashboard-cards-item_bg"></div>

                    <div class="dashboard-cards-item_title">
                        TOTAL SECTIONS
                    </div>

                    <div class="dashboard-cards-item_number-box">
                        <span class="dashboard-cards-item_number">
                            {{ number_format($section) }}
                        </span>
                        <span class="dashboard-cards-item_text">
                            ALL STRANDS
                        </span>
                    </div>
                </a>
            </div>

            <div class="dashboard-cards_item active-school-year">
                <a href="#" class="dashboard-cards-item_link">
                    <div class="dashboard-cards-item_bg_year"></div>
                    <div class="dashboard-cards-item_title">
                        ACTIVE SCHOOL YEAR
                    </div>

                    <div class="dashboard-cards-item_number-box">
                        <span class="dashboard-cards-item_year">
                            @if($activeYear)
                                {{ $activeYear->yearStart }} - {{ $activeYear->yearEnd }}
                            @else
                                No active year
                            @endif
                        </span>
                        <span class="dashboard-cards-item_text">
                            SCHOOL YEAR
                        </span>
                    </div>
                </a>
            </div>


        </div>
    </div>

    <div class="modal fade" id="welcomeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="col-md-6 d-flex justify-content-center">
                        <img src="{{ asset('/image/modal.png') }}" class="modal-img mt-2" alt="logo">
                    </div>
                    <div class="col modal-text mb-3 mt-3">
                        <p>Welcome to <strong>UMIANKonek</strong>! We're thrilled to have you on board for your Senior High
                            School journey. As part of your first login, please take a moment to <strong>change your
                                username and password</strong> for your account's security. This platform is designed to
                            make enrollment simple, fast, and stress-free. From choosing your strand to staying connected
                            with your school community, everything is just a few clicks away.</p>
                    </div>
                    <a class="btn btn-modal" href="{{ route('AdminComponents.setting') }}">Continue</a>
                </div>
            </div>
        </div>
    </div>

    @if(Auth::user() && Auth::user()->test == 0)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modal = new bootstrap.Modal(document.getElementById('welcomeModal'));
                modal.show();
            });
        </script>
    @endif

@endsection
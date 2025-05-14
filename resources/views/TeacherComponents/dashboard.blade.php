@extends('Dashboard.teacher')

@section('title', 'UMIANKonek | teacher')

@section('content')
<header>
    <h1 class="d-flex justify-content-center align-text-center ">Welcome, {{ $user->firstName ?? '' }} {{ $user->middleName ?? '' }} {{ $user->lastName ?? '' }}!</h1>

</header>
<div class="dashboard-container">
    <div class="dashboard-cards_box">

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

                <div class="col modal-text mb-3 mt-3">
                    <p>Welcome to <strong>UMIANKonek</strong>! We're thrilled to have you on board for your Senior High
                        School journey. As part of your first login, please take a moment to <strong>change your
                            username and password</strong> for your account's security. This platform is designed to
                        make enrollment simple, fast, and stress-free. From choosing your strand to staying connected
                        with your school community, everything is just a few clicks away.</p>
                </div>
                <a class="btn btn-modal" href="{{ route('TeacherComponents.setting') }}">Continue</a>
            </div>
        </div>
    </div>
</div>

@if(Auth::user() && Auth::user()->test == 0)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = new bootstrap.Modal(document.getElementById('welcomeModal'));
        modal.show();
    });
</script>
@endif

@endsection
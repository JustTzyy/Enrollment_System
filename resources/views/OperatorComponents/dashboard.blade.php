@extends('Dashboard.operator')

@section('title', 'UMIANKonek | Operator')

@section('operatorcontent')
    <header>
        <h1 class="d-flex justify-content-center align-text-center ">Welcome, {{ Cookie::get('username') }}!</h1>
        <p class="d-flex justify-content-center align-text-center ">SY: @if($activeYear)
            {{ $activeYear->yearStart }}-{{ $activeYear->yearEnd }}
        @else
            No active year
        @endif
        </p>
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

@endsection
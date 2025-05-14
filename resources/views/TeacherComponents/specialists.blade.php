@extends('Dashboard.teacher')
@section('title', 'UMIANKonek | Teacher')



<style>
    .credentials-container {
        max-width: 1200px;
        margin: 20px auto;
        background-color: #ffffff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        border-radius: 8px;
        overflow: hidden;
    }

    .credentials-header {
        background-color: #b02532;
        padding: 18px 25px;
        border-bottom: 1px solid #dee2e6;
        border-radius: 8px 8px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .credentials-header h2 {
        margin: 0;
        color: #ffffff;
        font-size: 1.5rem;
        font-weight: 600;
    }

    .teacher-info-badge {
        background: rgba(255, 255, 255, 0.15);
        padding: 5px 12px;
        border-radius: 20px;
        color: #fff;
        font-size: 0.85rem;
    }

    .credentials-content {
        padding: 25px;
    }

    .teacher-profile {
        display: flex;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eaeaea;
    }

    .teacher-avatar {
        width: 80px;
        height: 80px;
        background-color: #e9ecef;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 20px;
        color: #6c757d;
        font-size: 2rem;
        font-weight: bold;
    }

    .teacher-details {
        flex: 1;
    }

    .teacher-name {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 5px;
        color: #2c3e50;
    }

    .teacher-id {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 10px;
    }

    .specialty-section {
        margin-top: 15px;
    }

    .specialty-header {
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .specialty-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #2c3e50;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .specialty-count {
        background-color: #2c3e50;
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .subject-cards {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }

    .subject-card {
        background-color: #f8f9fa;
        border-left: 4px solid #2c3e50;
        border-radius: 5px;
        padding: 15px;
        transition: all 0.3s ease;
    }

    .subject-card:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .subject-name {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
        font-size: 1.05rem;
    }

    .subject-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.85rem;
        color: #6c757d;
    }

    .subject-code {
        font-weight: 600;
    }

    .department-tag {
        background-color: #e9ecef;
        padding: 3px 10px;
        border-radius: 15px;
        font-size: 0.8rem;
    }

    .specialty-tag {
        position: absolute;
        top: -5px;
        right: -5px;
        background-color: #28a745;
        color: white;
        padding: 3px 10px;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .subject-wrapper {
        position: relative;
    }

    .footer-section {
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #eaeaea;
        display: flex;
        justify-content: flex-end;
    }

    .total-subjects {
        display: flex;
        align-items: center;
        font-size: 1rem;
    }

    .total-label {
        font-weight: 600;
        margin-right: 10px;
        color: #2c3e50;
    }

    .total-value {
        background-color: #2c3e50;
        color: white;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-weight: 600;
    }
</style>
@section('content')

<div class="credentials-container">
    <div class="credentials-header">
        <h2>Teaching Specialization</h2>
        <div class="teacher-info-badge">{{ $user->id }}</div>
    </div>
    <div class="credentials-content">
        <!-- Teacher Profile Section -->
        <div class="teacher-profile">
            <div class="teacher-avatar">
                {{ substr($user->firstName, 0, 1) }}{{ substr($user->lastName, 0, 1) }}
            </div>
            <div class="teacher-details">
                <div class="teacher-name">{{ strtoupper($user->lastName . ', ' . $user->firstName . ' ' . $user->middleName) }}</div>
                <div class="teacher-id">Teacher ID: {{ $user->id }}</div>
            </div>
        </div>

        <!-- Specialty Section -->
        <div class="specialty-section">
            <div class="specialty-header">
                <div class="specialty-title">Assigned Subjects</div>
                <div class="specialty-count">{{ $assignedSubjects->count() }}</div>
            </div>

            <div class="subject-cards">
                @forelse ($assignedSubjects as $subject)
                <div class="subject-wrapper">
                    <div class="subject-card">
                        <div class="subject-name">{{ $subject->subject }}</div>
                        <div class="subject-info">
                            <span class="subject-code">{{ $subject->code }}</span>
                            <span class="department-tag">Depp</span>
                        </div>
                    </div>
                    <span class="specialty-tag">Specialty</span>
                </div>
                @empty
                <p>No assigned subjects found.</p>
                @endforelse
            </div>
        </div>

        <!-- Footer Section -->
        <div class="footer-section">
            <div class="total-subjects">
                <div class="total-label">Total Subjects:</div>
                <div class="total-value">{{ $assignedSubjects->count() }}</div>
            </div>
        </div>
    </div>
</div>


@endsection
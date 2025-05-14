@extends('Dashboard.student')
@section('title', 'UMIANKonek | Student')
<style>
    .credentials-container {
        max-width: 1200px;
        margin: 20px auto;
        background-color: #ffffff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 4px;
    }

    .credentials-header {
        background-color: #f8f9fa;
        padding: 15px 20px;
        border-bottom: 1px solid #dee2e6;
        border-radius: 4px 4px 0 0;
    }

    .credentials-header h2 {
        margin: 0;
        color: #333;
        font-size: 1.5rem;
    }

    .credentials-content {
        padding: 20px;
    }

    .credential-card {
        border: 1px solid #dee2e6;
        border-radius: 4px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .info-row {
        display: flex;
        margin-bottom: 15px;
    }

    .info-label {
        font-weight: bold;
        width: 120px;
    }

    .info-value {
        flex: 1;
        border-bottom: 1px solid #dee2e6;
        padding-bottom: 3px;
        margin-left: 10px;
    }

    .submitted-documents {
        margin-top: 20px;
    }

    .submitted-documents h3 {
        font-size: 1.1rem;
        text-transform: uppercase;
        margin-bottom: 15px;
    }

    .document-item {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .document-checkbox {
        margin-right: 10px;
    }

    .document-name {
        margin-right: 10px;
        flex: 1;
    }

    .submitted-tag {
        background-color: #28a745;
        color: white;
        padding: 2px 8px;
        border-radius: 3px;
        font-size: 0.8rem;
    }

    .watermark {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-45deg);
        font-size: 100px;
        opacity: 0.1;
        color: #b02532;
        font-weight: bold;
        pointer-events: none;
        z-index: 1;
        white-space: nowrap;
    }

    .credentials-card {
        position: relative;
        padding: 20px;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        margin-top: 20px;
        overflow: hidden;
    }

    .total-row {
        display: flex;
        margin-top: 20px;
        border-top: 1px solid #dee2e6;
        padding-top: 10px;
    }

    .total-label {
        font-weight: bold;
        margin-right: 10px;
    }

    .total-value {
        width: 100px;
        border-bottom: 1px solid #dee2e6;
        text-align: center;
    }
</style>
@section('content')

<<div class="credentials-container">
    <div class="credentials-header">
        <h2>Credentials</h2>
    </div>
    <div class="credentials-content">
        <div class="credentials-card">
            <div class="info-row">
                <div class="info-label">Name:</div>
                <div class="info-value">{{ strtoupper($user->lastName . ' ' . $user->firstName . ' ' . $user->middleName) }}</div>
                <div class="info-label" style="margin-left: 20px;">Account/ID #:</div>
                <div class="info-value">{{ $user->id }}</div>
            </div>

            <div class="submitted-documents">
                <h3>SUBMITTED DOCUMENTS</h3>

                @foreach ($submittedRequirements as $sr)
                <div class="document-item">
                    <input type="checkbox" class="document-checkbox" checked disabled>
                    <div class="document-name">{{ $sr->requirement->name }}</div>
                    <span class="submitted-tag">Submitted</span>
                </div>
                @endforeach

                <div class="total-row">
                    <div class="total-label">Total number of credentials:</div>
                    <div class="total-value">{{ $submittedRequirements->count() }}</div>
                </div>
            </div>
        </div>
    </div>
    </div>


    @endsection
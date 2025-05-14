@extends('Dashboard.student')
@section('title', 'UMIANKonek | Student')
@section('content')

@if(isset($error))
    <div class="alert alert-warning text-center my-4">
        {{ $error }}
    </div>
@endif

<div class="transcript-container">
    <div class="transcript-header">
        <h2>Academic Transcript</h2>
        <div class="print-button">
            <button onclick="window.print()">
                <i class="fas fa-print"></i> Print Transcript
            </button>
        </div>
    </div>

    <div class="student-info">
        <div class="info-group">
            <div class="info-label">Student Name:</div>
            <div class="info-value">{{ $student->lastName ?? 'Last Name' }}, {{ $student->firstName ?? 'First Name' }} {{ $student->middleName ?? 'Middle Name' }}</div>
        </div>
        <div class="info-group">
            <div class="info-label">Student No:</div>
            <div class="info-value">{{ $student->id ?? 'STD-00001' }}</div>
        </div>
        <div class="info-group">
            <div class="info-label">Strand:</div>
            <div class="info-value">{{ $student->strand ?? 'STEM' }}</div>
        </div>
    </div>

    <div class="transcript-body">
        <table class="transcript-table">
            <thead>
                <tr>
                    <th width="15%">Course Number</th>
                    <th width="70%">Descriptive Title of Course</th>
                    <th width="15%">Final Grade</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($subjects as $gradeLevel => $semesters)
                @foreach ($semesters as $semester => $subList)
                <tr class="semester-header">
                    <td colspan="3">GRADE {{ $gradeLevel }} - {{ strtoupper($semester) }} SEMESTER</td>
                </tr>
                @foreach ($subList as $subject)
                <tr class="subject-row">
                    <td>{{ $subject->code ?? 'N/A' }}</td>
                    <td>{{ $subject->subject ?? 'Untitled Subject' }}</td>
                    <td class="grade-column">00</td> {{-- Placeholder for grade --}}
                </tr>
                @endforeach
                <tr class="semester-summary">
                    <td colspan="2" class="text-right">Semester Average:</td>
                    <td class="grade-column">00.00</td>
                </tr>
                @endforeach
                <tr class="grade-level-summary">
                    <td colspan="2" class="text-right">Grade {{ $gradeLevel }} Average:</td>
                    <td class="grade-column">00.00</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="no-data">No subjects found for this strand.</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="overall-average">
                    <td colspan="2" class="text-right">Overall General Average:</td>
                    <td class="grade-column">00.00</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="transcript-footer">
        <div class="signature-section">
            <div class="signature-line">
                <div class="signatory">____________________</div>
                <div class="signatory-title">Registrar</div>
            </div>
            <div class="signature-line">
                <div class="signatory">____________________</div>
                <div class="signatory-title">Principal</div>
            </div>
        </div>
        <div class="remarks-section">
            <p class="note">This is an unofficial transcript of records. Not valid without the school seal and signature of authorized personnel.</p>
        </div>
    </div>
</div>

<style>
    /* Main Container */
    .transcript-container {
        max-width: 1200px;
        margin: 20px auto;
        background-color: #ffffff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 4px;
        overflow: hidden;
        font-family: Arial, sans-serif;
        display: flex;
        flex-direction: column;
        height: 80vh;
        /* Set height to 80% of viewport height */
    }

    /* Header Section */
    .transcript-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        border-bottom: 1px solid #b02532;
        color: #b02532;
        flex-shrink: 0;
        /* Prevent header from shrinking */
    }

    .transcript-header h2 {
        margin: 0;
        font-size: 1.5rem;
    }

    .print-button button {
        background-color: #ffd64e;
        border: none;
        color: #b02532;
        padding: 8px 15px;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* Student Info Section */
    .student-info {
        display: flex;
        justify-content: space-between;
        padding: 15px 20px;
        border-bottom: 1px solid #b02532;
        background-color: #f9f9f9;
        flex-shrink: 0;
        /* Prevent student info from shrinking */
    }

    .info-group {
        display: flex;
        gap: 10px;
    }

    .info-label {
        font-weight: bold;
        color: #333;
    }

    .info-value {
        color: #b02532;
    }

    /* Table Styling */
    .transcript-body {
        padding: 0;
        overflow-y: auto;
        /* Enable vertical scrolling */
        flex-grow: 1;
        /* Allow this section to grow and take available space */
    }

    .transcript-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 0;
        table-layout: fixed;
        /* Fixed table layout for better column control */
    }

    /* Sticky header */
    .transcript-table thead {
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .transcript-table th {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 2px solid #b02532;
        background-color: #ffd64e;
        font-weight: bold;
        color: #b02532;
    }

    .transcript-table td {
        padding: 10px 15px;
        text-align: left;
        border-bottom: 1px solid #eaeaea;
    }

    .semester-header {
        background-color: #f9f9f9;
        border-left: 5px solid #b02532;
        font-weight: bold;
        color: #b02532;
    }

    .subject-row:hover {
        background-color: #f5f5f5;
    }

    .grade-column {
        text-align: center;
        font-weight: bold;
    }

    .semester-summary {
        background-color: #f7f7f7;
        font-weight: bold;
    }

    .text-right {
        text-align: right;
    }

    .grade-level-summary {
        background-color: #efefef;
        font-weight: bold;
        border-bottom: 2px solid #b02532;
    }

    .overall-average {
        background-color: #ffd64e;
        color: #b02532;
        font-weight: bold;
        font-size: 1.1em;
    }

    .no-data {
        text-align: center;
        padding: 30px;
        font-style: italic;
        color: #777;
    }

    /* Footer Section */
    .transcript-footer {
        padding: 20px;
        border-top: 1px solid #b02532;
        flex-shrink: 0;
        /* Prevent footer from shrinking */
    }

    .signature-section {
        display: flex;
        justify-content: space-around;
        margin: 30px 0;
    }

    .signature-line {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 200px;
    }

    .signatory {
        border-bottom: 1px solid #333;
        width: 100%;
        text-align: center;
        padding-bottom: 5px;
    }

    .signatory-title {
        margin-top: 5px;
        font-weight: bold;
    }

    .remarks-section {
        text-align: center;
        margin-top: 20px;
        font-size: 0.9em;
        font-style: italic;
        color: #777;
    }

    /* Print Styles */
    @media print {
        .print-button {
            display: none;
        }

        .transcript-container {
            box-shadow: none;
            margin: 0;
        }
    }
</style>
@endsection
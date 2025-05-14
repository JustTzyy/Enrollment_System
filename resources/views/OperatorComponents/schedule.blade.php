@extends('Dashboard.operator')

@section('title', 'UMIANKonek | Operator')

@section('operatorcontent')
  <header class="text-center mb-2">
    <h1>YEAR & CURRICULUM</h1>
    <p>SECTIONS</p>
  </header>
  @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif
  @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <ul class="mb-0">
    @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
  @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  {{-- SEARCH/ADD --}}
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
    <!-- Search Input -->
    <div class="search-container">
      <form action="{{ route('OperatorComponents.schedule') }}" method="GET">
        <div class="d-flex align-items-center">
          <input type="text" name="search" class="search-input" placeholder="Search..." value="{{ request('search') }}">
          <i class="fas fa-search search-icon"></i>
        </div>
      </form>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table custom-table mt-2">
    <thead class="thead-dark">
      <tr>
      <th scope="col">ID</th>
      <th scope="col">Section Name</th>
      <th scope="col">Strand</th>
      <th scope="col">Description</th>
      <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($sections as $section)
      <tr>
      <th scope="row">{{ $section->id }}</th>
      <td>{{ $section->section }}</td>
      <td>{{ $section->strand->strand }}</td>
      <td>
      <div class="d-flex justify-content-center gap-2">
    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
      data-bs-target="#sectionDetailsModal{{ $section->id }}">
      <i class="fas fa-info-circle me-1"></i>Details
    </button>
    <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal"
      data-bs-target="#subjectsModal{{ $section->id }}">
      <i class="fas fa-book me-1"></i>Subjects
    </button>
    <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal"
      data-bs-target="#schedulesModal{{ $section->id }}">
      <i class="fas fa-calendar-alt me-1"></i>Schedule
    </button>
  </div>

      <!-- SCHEDULE VIEW MODAL -->
<div class="modal fade" id="schedulesModal{{ $section->id }}" tabindex="-1" aria-labelledby="schedulesLabel{{ $section->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="schedulesLabel{{ $section->id }}">
          <i class="fas fa-calendar-alt me-2"></i>Schedule for {{ $section->section }}
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        @if ($section->schedule->isNotEmpty())
          <div class="card border-0 shadow-sm">
            <div class="card-body">
              <div class="d-flex align-items-center mb-3">
                <i class="fas fa-clock text-primary me-2"></i>
                <h6 class="card-title mb-0">Class Schedule</h6>
              </div>
              <div class="table-responsive">
                <table class="table table-hover table-bordered">
                  <thead class="table-light">
                    <tr>
                      <th class="text-center" style="width: 20%">Time</th>
                      <th class="text-center" style="width: 15%">Day</th>
                      <th class="text-center" style="width: 35%">Subject</th>
                      <th class="text-center" style="width: 30%">Teacher</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($section->schedule as $sched)
                      <tr>
                        <td class="text-center">
                          <i class="far fa-clock text-primary me-1"></i>
                          {{ \Carbon\Carbon::parse($sched->startTime)->format('h:i A') }}
                        </td>
                        <td class="text-center">
                          <span class="badge bg-info">{{ $sched->day }}</span>
                        </td>
                        <td>
                          <i class="fas fa-book text-primary me-1"></i>
                          {{ $sched->subjectAssignment->subject->subject ?? 'N/A' }}
                        </td>
                        <td>
                          <i class="fas fa-user text-primary me-1"></i>
                          {{ $sched->user->name ?? 'Unassigned' }}
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        @else
          <div class="alert alert-info mb-0">
            <i class="fas fa-info-circle me-2"></i>
            No schedules available for this section.
          </div>
        @endif
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fas fa-times me-1"></i>Close
        </button>
      </div>
    </div>
  </div>
</div>


      </td>
      <td class="d-flex justify-content-center gap-2">
         @if($section->schedule && $section->schedule->isNotEmpty())
    <button class="btn btn-outline-primary btn-sm"
      onclick="if(confirm('Are you sure you want to edit this section?')) { 
        var myModal = new bootstrap.Modal(document.getElementById('updateSectionModal{{ $section->id }}')); 
        myModal.show(); 
      }">
      <i class="fas fa-pen me-1"></i>Edit
    </button>
  @else
    <button class="btn btn-outline-primary btn-sm" disabled data-bs-toggle="tooltip" data-bs-placement="top" title="No schedule available to edit">
      <i class="fas fa-pen me-1"></i>Edit
    </button>
  @endif

        <form action="{{ route('section.generateSchedule', $section->id) }}" method="POST"
          onsubmit="return confirm('Generate schedule for this section?')">
          @csrf
          <button type="submit" class="btn btn-outline-success btn-sm">
            <i class="fas fa-magic me-1"></i>Generate
          </button>
        </form>
      </td>



      <div class="modal fade" id="subjectsModal{{ $section->id }}" tabindex="-1"
  aria-labelledby="subjectsModalLabel{{ $section->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">
          <i class="fas fa-book me-2"></i>Subjects for {{ $section->section }}
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        @php
          $subjectAssignments = \App\Models\SubjectAssignment::with('subject')
              ->where('strandID', $section->strandID)
              ->where('gradeLevel', $section->gradeLevel)
              ->where('semester', $section->semester)
              ->orderBy('subjectID')
              ->get();
        @endphp

        <div class="card border-0 shadow-sm">
          <div class="card-body">
            <div class="d-flex align-items-center mb-3">
              <i class="fas fa-graduation-cap text-primary me-2"></i>
              <h6 class="card-title mb-0">Grade {{ $section->gradeLevel }}, Semester {{ $section->semester }}</h6>
            </div>

            @if($subjectAssignments->isEmpty())
              <div class="alert alert-info mb-0">
                <i class="fas fa-info-circle me-2"></i>
                No subjects assigned to this section.
              </div>
            @else
              <div class="list-group list-group-flush">
                @foreach($subjectAssignments as $subjectAssignment)
                  <div class="list-group-item d-flex align-items-center py-2">
                    <i class="fas fa-bookmark text-primary me-2"></i>
                    <span>{{ $subjectAssignment->subject->subject }}</span>
                  </div>
                @endforeach
              </div>
            @endif
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fas fa-times me-1"></i>Close
        </button>
      </div>
    </div>
  </div>
</div>




      <!-- Modal -->
      <div class="modal fade" id="sectionDetailsModal{{ $section->id }}" tabindex="-1"
      aria-labelledby="sectionDetailsModalLabel{{ $section->id }}" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content shadow-lg border-0">
        <div class="modal-header bg-info text-white">
          <h5 class="modal-title d-flex align-items-center" id="sectionDetailsModalLabel{{ $section->id }}">
            <i class="fas fa-info-circle me-2"></i>Section Details
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-start">
          <div class="card border-0">
            <div class="card-body">
              <div class="mb-3">
                <h6 class="fw-bold text-info mb-2"><i class="fas fa-book me-2 text-info"></i>Section Name</h6>
                <div class="ps-4 text-justify">{{ $section->section }}</div>
              </div>
              <div class="mb-3">
                <h6 class="fw-bold text-info mb-2"><i class="fas fa-door-open me-2 text-info"></i>Room</h6>
                <div class="ps-4 text-justify">{{ $section->room }}</div>
              </div>
              <div class="mb-3">
                <h6 class="fw-bold text-info mb-2"><i class="fas fa-layer-group me-2 text-info"></i>Strand</h6>
                <div class="ps-4 text-justify">{{ $section->strand->strand }}</div>
              </div>
              <div class="mb-3">
                <h6 class="fw-bold text-info mb-2"><i class="fas fa-align-left me-2 text-info"></i>Description</h6>
                <div class="ps-4 text-justify">{{ $section->description }}</div>
              </div>
              <div class="mb-3">
                <h6 class="fw-bold text-info mb-2"><i class="fas fa-calendar-alt me-2 text-info"></i>Semester</h6>
                <div class="ps-4 text-justify">{{ $section->semester }}</div>
              </div>
              <div>
                <h6 class="fw-bold text-info mb-2"><i class="fas fa-graduation-cap me-2 text-info"></i>Grade Level</h6>
                <div class="ps-4 text-justify">{{ $section->gradeLevel }}</div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
      </div>
      </div>


   



    @empty
      <tr>
      <td colspan="6">No Section available</td>
      </tr>
    @endforelse
    </tbody>
    </table>

    <!-- Pagination -->
    <nav aria-label="Page navigation example">
    <ul class="pagination">
      @if ($sections->onFirstPage())
      <li class="page-item disabled"><span class="page-link">Previous</span></li>
    @else
      <li class="page-item">
      <a class="page-link" href="{{ $sections->previousPageUrl() }}">Previous</a>
      </li>
    @endif

      @foreach ($sections->links()->elements[0] as $page => $url)
      <li class="page-item {{ $sections->currentPage() == $page ? 'active' : '' }}">
      <a class="page-link" href="{{ $url }}">{{ $page }}</a>
      </li>
    @endforeach

      @if ($sections->hasMorePages())
      <li class="page-item">
      <a class="page-link" href="{{ $sections->nextPageUrl() }}">Next</a>
      </li>
    @else
      <li class="page-item disabled"><span class="page-link">Next</span></li>
    @endif
    </ul>
    </nav>
  </div>


@endsection
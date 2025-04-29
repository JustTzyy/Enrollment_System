@extends('Dashboard.admin')

@section('title', 'UMIANKonek | Admin')

@section('content')
    <header class="text-center mb-2">
        <h1>YEAR & CURRICULUM</h1>
        <p>SUBJECT ASSIGNMENTS</p>
    </header>

    {{-- Success/Error Alerts --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Search & Add --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="search-container">
            <form action="{{ route('AdminComponents.subjectassignment') }}" method="GET">
                <div class="d-flex align-items-center">
                    <input type="text" name="search" class="search-input" placeholder="Search..."
                        value="{{ request('search') }}">
                    <i class="fas fa-search search-icon"></i>
                </div>
            </form>
        </div>

        <div class="d-flex align-items-end ms-auto">
            <button class="btn btncustom mx-2" data-bs-toggle="modal" data-bs-target="#addAssignmentModal">
                New Assignment
            </button>
            <button type="button" class="btn btnarchive mx-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Archived
            </button>
        </div>
    </div>

    {{-- Table --}}
    <div class="table-responsive mt-3">
    @php
    // Group this page’s assignments by strandID, so each strand appears once
    $assignmentsByStrand = $assignments->groupBy('strandID');
@endphp

<table class="table custom-table">
    <thead class="thead-dark">
        <tr>
            <th>Strand</th>
            <th># Subjects</th>
            <th>Details</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($assignmentsByStrand as $strandId => $strandAssignments)
            @php 
                $strand = $strandAssignments->first()->strand;
                $firstAssignment = $strandAssignments->first();
            @endphp
            <tr>
                <td>{{ $strand->strand }}</td>
                <td>{{ $strandAssignments->count() }}</td>
                <td>
                    <button class="btn btn-info btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#strandDetailsModal{{ $strandId }}">
                        See More
                    </button>
                </td>
                <td class="d-flex justify-content-center gap-2">
                    {{-- Edit the **first** assignment for this strand --}}
                    <button class="btn btn-primary btn-sm"
                            onclick="if(confirm('Update this assignment?')) {
                               var myModal = new bootstrap.Modal(document.getElementById('editSubjectsModal{{ $strandId }}'));
                               myModal.show();
                             }">
                        <i class="fa-solid fa-pen"></i>
                    </button>

                    <form action="{{ route('subjectassignment.destroy', $firstAssignment->id) }}"
                          method="POST"
                          onsubmit="return confirm('Delete this assignment?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm">
                          <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4">No strand assignments available.</td>
            </tr>
        @endforelse
    </tbody>
</table>

@php
  // group assignments by strand so we can edit per-strand
  $assignmentsByStrand = $assignments->groupBy('strandID');
@endphp

@foreach($assignmentsByStrand as $strandId => $strandAssignments)
  @php $strand = $strandAssignments->first()->strand; @endphp

 <!-- Edit Subjects Modal -->
<div class="modal fade" id="editSubjectsModal{{ $strandId }}" tabindex="-1">
  <div class="modal-dialog">
    <form action="{{ route('subjectassignment.update', $strandId) }}" 
          method="POST" 
          class="modal-content"
          onsubmit="return validateSubjectEdit{{ $strandId }}()">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Edit Subjects for {{ $strand->strand }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-start">
        <!-- Subject Input -->
        <label for="subjectInput{{ $strandId }}" class="form-label">
          Choose or enter subjects:
        </label>
        <input list="subjectOptions{{ $strandId }}"
               id="subjectInput{{ $strandId }}"
               class="form-control mb-2"
               placeholder="Type a subject and press Enter or Comma">
        <datalist id="subjectOptions{{ $strandId }}">
          @foreach($subjects as $subject)
            <option data-id="{{ $subject->id }}" value="{{ $subject->subject }}"></option>
          @endforeach
        </datalist>

        <div id="selectedSubjects{{ $strandId }}" class="mb-2 d-flex flex-wrap gap-2">
          @foreach($strandAssignments as $sa)
            <span class="badge bg-primary d-flex align-items-center gap-1 px-2 py-1">
              {{ $sa->subject->subject }}
              <button type="button" class="btn-close btn-close-white btn-sm ms-1" aria-label="Remove"></button>
              <input type="hidden" name="subjectIDs[]" value="{{ $sa->subjectID }}">
            </span>
          @endforeach
        </div>
        <div id="subjectError{{ $strandId }}" class="text-danger" style="display:none">
          Please select at least one valid subject.
        </div>

        <!-- Time Limit Dropdown -->
        <label for="timeLimit{{ $strandId }}" class="form-label">Select Time Limit:</label>
        <select id="timeLimit{{ $strandId }}" name="timeLimit" class="form-control mb-2" required>
            <option value="30" {{ $strandAssignments->first()->timeLimit == 30 ? 'selected' : '' }}>30 Minutes</option>
            <option value="60" {{ $strandAssignments->first()->timeLimit == 60 ? 'selected' : '' }}>1 Hour</option>
            <option value="90" {{ $strandAssignments->first()->timeLimit == 90 ? 'selected' : '' }}>1.5 Hours</option>
            <option value="120" {{ $strandAssignments->first()->timeLimit == 120 ? 'selected' : '' }}>2 Hours</option>
        </select>

        <!-- Grade Level Dropdown -->
        <label for="gradeLevel{{ $strandId }}" class="form-label">Select Grade Level:</label>
        <select id="gradeLevel{{ $strandId }}" name="gradeLevel" class="form-control mb-2" required>
            <option value="Grade 11" {{ $strandAssignments->first()->gradeLevel == 'Grade 11' ? 'selected' : '' }}>Grade 11</option>
            <option value="Grade 12" {{ $strandAssignments->first()->gradeLevel == 'Grade 12' ? 'selected' : '' }}>Grade 12</option>
        </select>

        <!-- Semester Dropdown -->
        <label for="semester{{ $strandId }}" class="form-label">Select Semester:</label>
        <select id="semester{{ $strandId }}" name="semester" class="form-control mb-2" required>
            <option value="Sem 1" {{ $strandAssignments->first()->semester == 'Sem 1' ? 'selected' : '' }}>Sem 1</option>
            <option value="Sem 2" {{ $strandAssignments->first()->semester == 'Sem 2' ? 'selected' : '' }}>Sem 2</option>
        </select>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success w-100">Update Subjects</button>
      </div>
    </form>
  </div>
</div>


  <script>
    document.addEventListener("DOMContentLoaded", () => {
      (function(){
        const strandId = "{{ $strandId }}";
        const input = document.getElementById("subjectInput"+strandId);
        const datalistOpts = document.querySelectorAll("#subjectOptions"+strandId+" option");
        const selectedContainer = document.getElementById("selectedSubjects"+strandId);
        const error = document.getElementById("subjectError"+strandId);
        const added = new Map();

        // Initialize map with existing subjects
        selectedContainer.querySelectorAll("input[type=hidden]").forEach(h => {
          const subjName = h.parentNode.firstChild.textContent.trim();
          added.set(subjName, h.value);
        });

        // Remove badge handler
        selectedContainer.addEventListener("click", e => {
          if (e.target.matches(".btn-close")) {
            const badge = e.target.closest("spanBadge");
            const h = badge.querySelector("input[type=hidden]");
            added.delete(h.value);
            badge.remove();
          }
        });

        // Add new subjects on Enter or Comma
        input.addEventListener("keydown", e => {
          if (e.key === "Enter" || e.key === ",") {
            e.preventDefault();
            const val = input.value.trim();
            input.value = "";
            if (!val || added.has(val)) return;

            let match = false;
            datalistOpts.forEach(opt => {
              if (opt.value === val) {
                match = true;
                const id = opt.getAttribute("data-id");
                added.set(val, id);

                const badge = document.createElement("span");
                badge.className = "badge bg-primary d-flex align-items-center gap-1 px-2 py-1";
                badge.innerHTML = ` 
                  ${val}
                  <button type="button" class="btn-close btn-close-white btn-sm ms-1" aria-label="Remove"></button>
                  <input type="hidden" name="subjectIDs[]" value="${id}">
                `;
                // handle remove
                badge.querySelector("button").addEventListener("click", () => {
                  added.delete(val);
                  badge.remove();
                });

                selectedContainer.appendChild(badge);
              }
            });

            if (!match) alert(`Subject "${val}" not recognized.`);
          }
        });

        // Form validation
        window["validateSubjectEdit"+strandId] = function() {
          if (added.size === 0) {
            error.style.display = "block";
            return false;
          }
          return true;
        };
      })();
    });
  </script>
{{-- One “See More” modal per teacher, listing ALL their subjects and strand --}}
@foreach($assignmentsByStrand as $strandId => $assignments)
  @php
    // Get the strand from the first assignment
    $strand = $assignments->first()->strand;
  @endphp
  <div class="modal fade" id="strandDetailsModal{{ $strandId }}" tabindex="-1" aria-labelledby="strandDetailsModalLabel{{ $strandId }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="strandDetailsModalLabel{{ $strandId }}">
            Subjects for Strand: {{ $strand->strand }}
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p><strong>Strand:</strong> {{ $strand->strand ?? '—' }}</p> {{-- Strand name --}}

        
          
          @if($assignments->isEmpty())
            <p><em>No assignments found.</em></p>
          @else
            <ul class="list-group">
              @foreach($assignments as $assignment)
                <li class="list-group-item">
                  <strong>{{ optional($assignment->subject)->subject ?? '—' }}</strong>
                  (Code: {{ $assignment->code }}) <br> (Sem: {{ $assignment->semester }}) (Time: {{ $assignment->time }}) <br> (Time: {{ $assignment->gradeLevel }}) <br>
                  Assigned on: {{ $assignment->created_at->format('F j, Y') }}

                  
                </li>
              @endforeach
            </ul>
          @endif
        </div>
      </div>
    </div>
  </div>
@endforeach

  
@endforeach




    <nav aria-label="Page navigation example">
        <ul class="pagination">
            @if ($strands->onFirstPage())
                <li class="page-item disabled"><span class="page-link">Previous</span></li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $strands->previousPageUrl() }}">Previous</a>
                </li>
            @endif

            @foreach ($strands->links()->elements[0] as $page => $url)
                <li class="page-item {{ $strands->currentPage() == $page ? 'active' : '' }}">
                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                </li>
            @endforeach

            @if ($strands->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $strands->nextPageUrl() }}">Next</a>
                </li>
            @else
                <li class="page-item disabled"><span class="page-link">Next</span></li>
            @endif
        </ul>
    </nav>
    </div>

 <!-- Add Modal -->
<div class="modal fade" id="addAssignmentModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('AdminComponents.subjectassignment') }}" method="POST" class="modal-content"
            onsubmit="return validateAssignmentSelection()">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">New Subject Assignment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-start">

                <!-- Subject Input -->
                <label class="form-label" for="subjectInput">Choose or enter a subject:</label>
                <input list="subjectOptions" id="subjectInput" class="form-control mb-2"
                    placeholder="Type a subject and press Enter or Comma">
                <datalist id="subjectOptions">
                    @foreach($subjects as $subject)
                        <option data-id="{{ $subject->id }}" value="{{ $subject->subject }}"></option>
                    @endforeach
                </datalist>
                <div id="selectedSubjects" class="mb-2 d-flex flex-wrap gap-2"></div>
                <!-- Hidden inputs will be appended here -->
                <div id="subjectIDsContainer"></div>
                <div class="text-danger" id="subjectError" style="display: none;">Please select at least one valid
                    subject.</div>

                <!-- Strand Input -->
                <label class="form-label" for="strandInput">Choose or enter a strand:</label>
                <input list="strandOptions" id="strandInput" class="form-control mb-2"
                    placeholder="Start typing strand name..." required>
                <datalist id="strandOptions">
                    @foreach($strands as $strand)
                        <option data-id="{{ $strand->id }}" value="{{ $strand->strand }}"></option>
                    @endforeach
                </datalist>

                <div id="selectedStrands" class="mb-2 d-flex flex-wrap gap-2"></div>
                <!-- Hidden inputs will be appended here -->
                <div id="strandIDsContainer"></div>
                <div class="text-danger" id="strandError" style="display: none;">Please select at least one valid
                    strand.</div>

                     <!-- Time Limit Dropdown -->
                <label for="timeLimit" class="form-label">Select Time Limit:</label>
                <select id="timeLimit" name="timeLimit" class="form-control mb-2" required>
                    <option value="30">30 Minutes</option>
                    <option value="60">1 Hour</option>
                    <option value="90">1.5 Hours</option>
                    <option value="120">2 Hours</option>
                </select>

                <!-- Grade Level Dropdown -->
                <label for="gradeLevel" class="form-label">Select Grade Level:</label>
                <select id="gradeLevel" name="gradeLevel" class="form-control mb-2" required>
                    <option value="Grade 11">Grade 11</option>
                    <option value="Grade 12">Grade 12</option>
                </select>

                <!-- Semester Dropdown -->
                <label for="semester" class="form-label">Select Semester:</label>
                <select id="semester" name="semester" class="form-control mb-2" required>
                    <option value="Sem 1">Sem 1</option>
                    <option value="Sem 2">Sem 2</option>
                </select>
                <!-- Script -->
                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        const subjectInput = document.getElementById("subjectInput");
                        const subjectHidden = document.getElementById("subjectID");
                        const subjectOptions = document.querySelectorAll("#subjectOptions option");

                        const strandInput = document.getElementById("strandInput");
                        const strandHidden = document.getElementById("strandID");
                        const strandOptions = document.querySelectorAll("#strandOptions option");

                        subjectInput.addEventListener("input", function () {
                            subjectHidden.value = '';
                            subjectOptions.forEach(option => {
                                if (option.value === subjectInput.value) {
                                    subjectHidden.value = option.getAttribute("data-id");
                                }
                            });
                        });

                        strandInput.addEventListener("input", function () {
                            strandHidden.value = '';
                            strandOptions.forEach(option => {
                                if (option.value === strandInput.value) {
                                    strandHidden.value = option.getAttribute("data-id");
                                }
                            });
                        });

                        teacherInput.addEventListener("input", function () {
                            teacherHidden.value = '';
                            teacherOptions.forEach(option => {
                                if (option.value === teacherInput.value) {
                                    teacherHidden.value = option.getAttribute("data-id");
                                }
                            });
                        });
                    });

                    document.addEventListener("DOMContentLoaded", () => {
                        const subjectInput = document.getElementById("subjectInput");
                        const subjectOptions = document.querySelectorAll("#subjectOptions option");
                        const selectedContainer = document.getElementById("selectedSubjects");
                        const subjectIDsContainer = document.getElementById("subjectIDsContainer");
                        const subjectError = document.getElementById("subjectError");

                        const addedSubjects = new Map(); // key: subjectName, value: subjectID

                        subjectInput.addEventListener("keydown", function (e) {
                            if (e.key === "Enter" || e.key === ",") {
                                e.preventDefault();
                                const inputVal = subjectInput.value.trim();
                                subjectInput.value = '';

                                if (inputVal === '' || addedSubjects.has(inputVal)) return;

                                let matched = false;
                                subjectOptions.forEach(option => {
                                    if (option.value === inputVal) {
                                        matched = true;
                                        const id = option.getAttribute("data-id");
                                        addedSubjects.set(inputVal, id);

                                        const badge = document.createElement("span");
                                        badge.className = "badge bg-primary d-flex align-items-center gap-1 px-2 py-1";
                                        badge.innerHTML = `
                                            ${inputVal}
                                            <button type="button" class="btn-close btn-close-white btn-sm ms-1" aria-label="Remove"></button>
                                        `;

                                        const hiddenInput = document.createElement("input");
                                        hiddenInput.type = "hidden";
                                        hiddenInput.name = "subjectIDs[]";
                                        hiddenInput.value = id;
                                        hiddenInput.setAttribute("data-subject", inputVal);

                                        // Remove on close
                                        badge.querySelector("button").addEventListener("click", () => {
                                            badge.remove();
                                            hiddenInput.remove();
                                            addedSubjects.delete(inputVal);
                                        });

                                        selectedContainer.appendChild(badge);
                                        subjectIDsContainer.appendChild(hiddenInput);
                                    }
                                });

                                if (!matched) {
                                    alert(`Subject "${inputVal}" is not recognized.`);
                                }
                            }
                        });

                        const strandInput = document.getElementById("strandInput");
                        const strandOptions = document.querySelectorAll("#strandOptions option");
                        const selectedStrandsContainer = document.getElementById("selectedStrands");
                        const strandIDsContainer = document.getElementById("strandIDsContainer");
                        const strandError = document.getElementById("strandError");

                        const addedStrands = new Map(); // key: strandName, value: strandID

                        strandInput.addEventListener("keydown", function (e) {
                            if (e.key === "Enter" || e.key === ",") {
                                e.preventDefault();
                                const inputVal = strandInput.value.trim();
                                strandInput.value = '';

                                if (inputVal === '' || addedStrands.has(inputVal)) return;

                                let matched = false;
                                strandOptions.forEach(option => {
                                    if (option.value === inputVal) {
                                        matched = true;
                                        const id = option.getAttribute("data-id");
                                        addedStrands.set(inputVal, id);

                                        const badge = document.createElement("span");
                                        badge.className = "badge bg-secondary d-flex align-items-center gap-1 px-2 py-1";
                                        badge.innerHTML = `
                                            ${inputVal}
                                            <button type="button" class="btn-close btn-close-white btn-sm ms-1" aria-label="Remove"></button>
                                        `;

                                        const hiddenInput = document.createElement("input");
                                        hiddenInput.type = "hidden";
                                        hiddenInput.name = "strandIDs[]";
                                        hiddenInput.value = id;
                                        hiddenInput.setAttribute("data-strand", inputVal);

                                        // Remove on close
                                        badge.querySelector("button").addEventListener("click", () => {
                                            badge.remove();
                                            hiddenInput.remove();
                                            addedStrands.delete(inputVal);
                                        });

                                        selectedStrandsContainer.appendChild(badge);
                                        strandIDsContainer.appendChild(hiddenInput);
                                    }
                                });

                                if (!matched) {
                                    alert(`Strand "${inputVal}" is not recognized.`);
                                }
                            }
                        });

                    });

                    function validateAssignmentSelection() {
                        const subjectIDs = document.getElementsByName("subjectIDs[]");
                        const strandIDs = document.getElementsByName("strandIDs[]");
                        const subjectError = document.getElementById("subjectError");
                        const strandError = document.getElementById("strandError");

                        if (subjectIDs.length === 0) {
                            subjectError.style.display = 'block';
                            return false;
                        }

                        if (strandIDs.length === 0) {
                            strandError.style.display = 'block';
                            return false;
                        }

                        subjectError.style.display = 'none';
                        strandError.style.display = 'none';
                        return true;
                    }
                </script>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success w-100">Assign</button>
            </div>
        </form>
    </div>
</div>


@endsection

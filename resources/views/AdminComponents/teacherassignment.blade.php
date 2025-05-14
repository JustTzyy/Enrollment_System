@extends('Dashboard.admin')

@section('title', 'UMIANKonek | Admin')

@section('content')
    <header class="text-center mb-2">
        <h1>YEAR & CURRICULUM</h1>
        <p>TEACHER ASSIGNMENTS</p>
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
            <form action="{{ route('AdminComponents.teacherassignment') }}" method="GET">
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
        </div>
    </div>

    {{-- Table --}}
    <div class="table-responsive mt-3">
    @php
    // Group this page's assignments by userID, so each teacher appears once
    $assignmentsByUser = $assignments->groupBy('userID');
@endphp

<table class="table custom-table">
    <thead class="thead-dark">
        <tr>
            <th>Teacher</th>
            <th># Subjects</th>
            <th>Details</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($users as $user)
            @php 
                $userAssignments = $assignments->where('userID', $user->id);
                $firstAssignment = $userAssignments->first();
            @endphp
            <tr>
                <td>
                    {{ $user->firstName }}
                    {{ $user->middleName }}
                    {{ $user->lastName }}
                </td>
                <td>{{ $userAssignments->count() }}</td>
                <td>
                    <button class="btn btn-outline-info btn-sm "
                            data-bs-toggle="modal"
                            data-bs-target="#userDetailsModal{{ $user->id }}">
                        <i class="fas fa-eye me-1"></i> See More
                    </button>
                </td>
                <td class="d-flex justify-content-center gap-2">
                    <button class="btn btn-outline-primary btn-sm d-flex align-items-center"
                        onclick="if(confirm('Update this assignment?')) { var myModal = new bootstrap.Modal(document.getElementById('editSubjectsModal{{ $user->id }}')); myModal.show(); }">
                        <i class="fas fa-pen me-1"></i> Edit
</button>

                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4">No teacher assignments available.</td>
            </tr>
        @endforelse
    </tbody>
</table>

@php
  // group assignments by teacher so we can edit per‐teacher
  $assignmentsByUser = $assignments->groupBy('userID');
@endphp

@foreach($users as $user)
    @php $userAssignments = $assignments->where('userID', $user->id); @endphp
    <!-- Edit Modal -->
    <div class="modal fade" id="editSubjectsModal{{ $user->id }}" tabindex="-1">
    <div class="modal-dialog">
            <form action="{{ route('teacherassignment.update', $user->id) }}"
            method="POST" 
            class="modal-content"
                  onsubmit="return validateSubjectEdit{{ $user->id }}()">
        @csrf
        <div class="modal-header">
                    <h5 class="modal-title">Edit Subjects for {{ $user->firstName }} {{ $user->lastName }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-start">
                    <label for="subjectInput{{ $user->id }}" class="form-label">
            Choose or enter subjects:
          </label>
                    <input list="subjectOptions{{ $user->id }}"
                           id="subjectInput{{ $user->id }}"
                 class="form-control mb-2"
                 placeholder="Type a subject and press Enter or Comma">
                    <datalist id="subjectOptions{{ $user->id }}">
            @foreach($subjects as $subject)
              <option data-id="{{ $subject->id }}" value="{{ $subject->subject }}"></option>
            @endforeach
          </datalist>
                    <div id="selectedSubjects{{ $user->id }}" class="mb-2 d-flex flex-wrap gap-2">
            @foreach($userAssignments as $ua)
              <span class="badge bg-primary d-flex align-items-center gap-1 px-2 py-1">
                {{ $ua->subject->subject }}
                                <button type="button" class="btn-close btn-close-white btn-sm ms-1 remove-subject-btn" aria-label="Remove"></button>
                                <input type="hidden" name="subjectIDs[]" value="{{ $ua->subjectID }}" data-subject="{{ $ua->subject->subject }}">
              </span>
            @endforeach
          </div>
                    <div id="subjectError{{ $user->id }}" class="text-danger" style="display:none">
            Please select at least one valid subject.
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success w-100">Update Subjects</button>
        </div>
      </form>
    </div>
  </div>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
        var subjectInput = document.getElementById("subjectInput{{ $user->id }}");
        var subjectOptions = document.querySelectorAll("#subjectOptions{{ $user->id }} option");
        var selectedContainer = document.getElementById("selectedSubjects{{ $user->id }}");

        if (subjectInput) {
            subjectInput.addEventListener("keydown", function(e) {
          if (e.key === "Enter" || e.key === ",") {
            e.preventDefault();
                    var inputVal = subjectInput.value.trim();
                    subjectInput.value = '';

                    if (inputVal === '') return;

                    // Check for duplicates
                    var alreadyAdded = Array.from(selectedContainer.querySelectorAll("input[name='subjectIDs[]']")).some(function(input) {
                        return input.getAttribute("data-subject") === inputVal;
                    });
                    if (alreadyAdded) return;

                    // Check if input matches a valid subject
                    var matched = false;
                    subjectOptions.forEach(function(option) {
                        if (option.value === inputVal) {
                            matched = true;
                            var id = option.getAttribute("data-id");

                            var badge = document.createElement("span");
                badge.className = "badge bg-primary d-flex align-items-center gap-1 px-2 py-1";
                badge.innerHTML = `
                                ${inputVal}
                                <button type="button" class="btn-close btn-close-white btn-sm ms-1 remove-subject-btn" aria-label="Remove"></button>
                            `;

                            var hiddenInput = document.createElement("input");
                            hiddenInput.type = "hidden";
                            hiddenInput.name = "subjectIDs[]";
                            hiddenInput.value = id;
                            hiddenInput.setAttribute("data-subject", inputVal);

                            badge.querySelector("button").addEventListener("click", function() {
                  badge.remove();
                                hiddenInput.remove();
                });

                selectedContainer.appendChild(badge);
                            selectedContainer.appendChild(hiddenInput);
              }
            });

                    if (!matched) {
                        alert(`Subject "${inputVal}" is not recognized.`);
          }
                }
            });
        }
    });
  </script>
    <!-- Details Modal -->
    <div class="modal fade" id="userDetailsModal{{ $user->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
                <div class="modal-header bg-info text-white">
          <h5 class="modal-title">
                        <i class="fas fa-eye me-2"></i>Subjects for {{ $user->firstName }} {{ $user->lastName }}
          </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-chalkboard-teacher text-info me-2"></i>
                                <h6 class="card-title mb-0">Assigned Subjects</h6>
                            </div>
          @if($userAssignments->isEmpty())
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle me-2"></i>
                                    No assignments found.
                                </div>
          @else
                                <ul class="list-group list-group-flush">
              @foreach($userAssignments as $ua)
                                        <li class="list-group-item d-flex align-items-center">
                                            <i class="fas fa-book text-info me-2"></i>
                                            <span class="fw-semibold">{{ optional($ua->subject)->subject ?? '—' }}</span>
                                            <span class="ms-auto text-muted small">(Code: {{ $ua->code }})</span>
                </li>
              @endforeach
            </ul>
          @endif
                        </div>
                    </div>
        </div>
      </div>
    </div>
  </div>
@endforeach




<nav aria-label="Page navigation example">
    <ul class="pagination">
        <!-- Previous Page Link -->
        @if ($users->onFirstPage())
            <li class="page-item disabled"><span class="page-link">Previous</span></li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $users->previousPageUrl() }}">Previous</a>
            </li>
        @endif

        <!-- Page Number Links -->
        @foreach ($users->links()->elements[0] as $page => $url)
            <li class="page-item {{ $users->currentPage() == $page ? 'active' : '' }}">
                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
            </li>
        @endforeach

        <!-- Next Page Link -->
        @if ($users->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $users->nextPageUrl() }}">Next</a>
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
            <form action="{{ route('AdminComponents.teacherassignment') }}" method="POST" class="modal-content"
                onsubmit="return validateSubjectSelection()">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">New Teacher Assignment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-start">

                    <!-- Subject Input -->
                    <label class="form-label" for="subjectInput">Choose or enter subjects:</label>
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

                    <!-- Teacher Input -->
                    <label class="form-label" for="teacherInput">Choose or enter a teacher:</label>
                    <input list="teacherOptions" id="teacherInput" class="form-control mb-2"
                        placeholder="Start typing teacher name..." required>
                    <input type="hidden" name="userID" id="teacherID">
                    <datalist id="teacherOptions">
                        @foreach($users2 as $user2)
                            <option data-id="{{ $user2->id }}"
                                value="{{ $user2->firstName }} {{ $user2->middleName }} {{ $user2->lastName }}"></option>
                        @endforeach
                    </datalist>

                    <!-- Script -->
                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            const subjectInput = document.getElementById("subjectInput");
                            const subjectHidden = document.getElementById("subjectID");
                            const subjectOptions = document.querySelectorAll("#subjectOptions option");

                            const teacherInput = document.getElementById("teacherInput");
                            const teacherHidden = document.getElementById("teacherID");
                            const teacherOptions = document.querySelectorAll("#teacherOptions option");

                            subjectInput.addEventListener("input", function () {
                                subjectHidden.value = '';
                                subjectOptions.forEach(option => {
                                    if (option.value === subjectInput.value) {
                                        subjectHidden.value = option.getAttribute("data-id");
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

                            // Teacher logic
                            const teacherInput = document.getElementById("teacherInput");
                            const teacherOptions = document.querySelectorAll("#teacherOptions option");
                            const teacherHidden = document.getElementById("teacherID");

                            teacherInput.addEventListener("input", function () {
                                teacherHidden.value = '';
                                teacherOptions.forEach(option => {
                                    if (option.value === teacherInput.value) {
                                        teacherHidden.value = option.getAttribute("data-id");
                                    }
                                });
                            });
                        });

                        function validateSubjectSelection() {
                            const subjectIDs = document.getElementsByName("subjectIDs[]");
                            const error = document.getElementById("subjectError");

                            if (subjectIDs.length === 0) {
                                error.style.display = 'block';
                                return false;
                            }

                            error.style.display = 'none';
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
@extends('Dashboard.admin')

@section('title', 'UMIANKonek | Admin')

@section('content')
  <header class="text-center mb-2">
    <h1>REPORT HISTORY</h1>
    <p>STUDENT ENROLLMENT HISTORY</p>
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
  <div class="d-flex justify-content-between align-items-center mt-5 flex-wrap gap-3">
    <!-- Search Input -->
    <div class="search-container">
    <form action="{{ route('AdminComponents.enrollment') }}" method="GET">
      <div class="d-flex align-items-center">
      <input type="text" name="search" class="search-input" placeholder="Search by student name..."
        value="{{ request('search') }}">
      <i class="fas fa-search search-icon"></i>
      </div>
    </form>
    </div>


    <!-- Button Group Aligned to End -->
    <div class="d-flex align-items-end ms-auto">
    <button type="button" class="btn btncustom mx-2" data-bs-toggle="modal" data-bs-target="#enrollModal">
      Enroll Student
    </button>
    </div>
  </div>

  {{-- Table --}}
  <div class="table-responsive mt-3">
    <table class="table custom-table">
    <thead class="thead-dark">
      <tr>
      <th>ID</th>
      <th>Fullname</th>
      <th>Grade Level</th>
      <th>Strand</th>
      <th>Section</th>
      <th>School Year</th>
      <th>Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($enrollments as $enrollment)
      <tr>
      <td>{{ $enrollment->id }}</td>
      <td>{{ $enrollment->user->lastName }}, {{ $enrollment->user->firstName }}</td>
      <td>{{ $enrollment->gradeLevel }}</td>
      <td>{{ $enrollment->strand->strand }}</td>
      <td>{{ $enrollment->section->section }}</td>
      <td>{{ \Carbon\Carbon::parse($enrollment->created_at)->format('F j, Y, h:i A') }}</td>
      <td class="d-flex justify-content-center gap-2">
          <button class="btn btn-outline-primary btn-sm d-flex align-items-center " title="Edit Enrollment" onclick="if(confirm('Are you sure you want to edit this enrollment?')) { var myModal = new bootstrap.Modal(document.getElementById('updateEnrollmentModal{{ $enrollment->id }}')); myModal.show(); }">
            <i class="fas fa-pen me-1"></i> Edit
          </button>
          <form action="{{ route('enrollment.destroy', $enrollment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this enrollment?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger btn-sm d-flex align-items-center" title="Delete Enrollment">
              <i class="fas fa-trash me-1"></i> Delete
            </button>
          </form>
      </td>
      </tr>

      <!-- Enrollment Update Modal -->
      <div class="modal fade" id="updateEnrollmentModal{{ $enrollment->id }}" tabindex="-1"
      aria-labelledby="updateEnrollmentModalLabel{{ $enrollment->id }}" aria-hidden="true">
      <div class="modal-dialog">
      <form action="{{ route('enrollment.update', $enrollment->id) }}" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="updateEnrollmentModalLabel{{ $enrollment->id }}">Update Enrollment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-start">

        <!-- Enrollment ID (hidden) -->
        <input type="hidden" name="id" value="{{ $enrollment->id }}">

        <!-- Student -->
        <div class="mb-3">
        <label for="userID" class="form-label">Student</label>
        <select name="userID" id="userID{{ $enrollment->id }}" class="form-control" required>
        <option value="">Select Student</option>
        @foreach($students as $student)
      <option value="{{ $student->id }}" @if($student->id == $enrollment->userID) selected @endif>
        {{ $student->lastName }}, {{ $student->firstName }}
      </option>

    @endforeach
        </select>
        </div>

        <!-- Grade Level -->
        <div class="mb-3">
        <label for="gradeLevel" class="form-label">Grade Level</label>
        <select name="gradeLevel" id="gradeLevel{{ $enrollment->id }}" class="form-control" required>
        <option value="">Select Grade</option>
        <option value="Grade 11" @if($enrollment->gradeLevel == 'Grade 11') selected @endif>Grade 11</option>
        <option value="Grade 12" @if($enrollment->gradeLevel == 'Grade 12') selected @endif>Grade 12</option>
        </select>
        </div>

         {{-- Semester --}}
         <div class="mb-3">
        <label class="form-label">Semester</label>
        <select name="semester" id="semester{{ $enrollment->id }}" class="form-control" required>
          <option value="Sem 1" @if($enrollment->semester == 'Sem 1') selected @endif>Sem 1</option>
          <option value="Sem 2" @if($enrollment->semester == 'Sem 2') selected @endif>Sem 2</option>
        </select>
        </div>

        <!-- Strand -->
        <div class="mb-3">
        <label for="strandID" class="form-label">Strand</label>
        <select name="strandID" id="strandID{{ $enrollment->id }}" class="form-control" required>
        @foreach($strands as $strand)
      <option value="{{ $strand->id }}" @if($strand->id == $enrollment->strandID) selected @endif>
        {{ $strand->strand }}
      </option>
    @endforeach
        </select>
        </div>

        <!-- Section (AJAX-populated) -->
        <div class="mb-3">
        <label for="sectionID" class="form-label">Section</label>
        <select name="sectionID" id="sectionID{{ $enrollment->id }}" class="form-control" required>
        <option value="">Select Section</option>
   
        </select>
        </div>

        {{-- Hidden Input for School Year --}}
        <input type="hidden" name="schoolYearID" value="{{ $activeSchoolYear->id }}">


        </div>
        <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Update</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
      </form>
      </div>
      </div>

      <script>
  $(document).ready(function () {
    // Trigger AJAX when strand, grade level, or semester changes inside any update modal
    $(document).on('change', 'select[id^="strandID"], select[id^="gradeLevel"], select[id^="semester"]', function () {
      var id = $(this).attr('id').replace(/\D/g, ''); // Extract numeric ID
      var strandID = $('#strandID' + id).val();
      var gradeLevel = $('#gradeLevel' + id).val();
      var semester = $('#semester' + id).val();
      var sectionSelect = $('#sectionID' + id);

      if (strandID && gradeLevel && semester) {
        $.ajax({
          url: "{{ route('get.sections.by.strand.and.grade') }}",
          type: "GET",
          data: {
            strand_id: strandID,
            grade_level: gradeLevel,
            semester: semester
          },
          success: function (response) {
            sectionSelect.empty().append('<option value="">Select Section</option>');
            if (response.length > 0) {
              response.forEach(function (section) {
                sectionSelect.append('<option value="' + section.id + '">' + section.section + '</option>');
              });

              // Auto-select currently assigned section if available
              var currentSection = sectionSelect.data('current');
              if (currentSection) {
                sectionSelect.val(currentSection);
              }
            } else {
              sectionSelect.append('<option value="">No sections available</option>');
            }
          }
        });
      }
    });

    // On modal show, preload section list
    $('[id^="updateEnrollmentModal"]').on('show.bs.modal', function (event) {
      var modal = $(this);
      var id = modal.attr('id').replace(/\D/g, '');

      var strandID = $('#strandID' + id).val();
      var gradeLevel = $('#gradeLevel' + id).val();
      var semester = $('#semester' + id).val();
      var sectionSelect = $('#sectionID' + id);
      var currentSectionID = sectionSelect.data('current');

      if (strandID && gradeLevel && semester) {
        $.ajax({
          url: "{{ route('get.sections.by.strand.and.grade') }}",
          type: "GET",
          data: {
            strand_id: strandID,
            grade_level: gradeLevel,
            semester: semester
          },
          success: function (response) {
            sectionSelect.empty().append('<option value="">Select Section</option>');
            if (response.length > 0) {
              response.forEach(function (section) {
                var selected = section.id == currentSectionID ? 'selected' : '';
                sectionSelect.append('<option value="' + section.id + '" ' + selected + '>' + section.section + '</option>');
              });
            } else {
              sectionSelect.append('<option value="">No sections available</option>');
            }
          }
        });
      }
    });
  });
</script>




    @endforeach



    </tbody>
    </table>

    <!-- Hereee -->
    <nav aria-label="Page navigation example">
    <ul class="pagination">
      @if ($enrollments->onFirstPage())
      <li class="page-item disabled"><span class="page-link">Previous</span></li>
    @else
      <li class="page-item">
      <a class="page-link" href="{{ $enrollments->previousPageUrl() }}">Previous</a>
      </li>
    @endif

      @foreach ($enrollments->links()->elements[0] as $page => $url)
      <li class="page-item {{ $enrollments->currentPage() == $page ? 'active' : '' }}">
      <a class="page-link" href="{{ $url }}">{{ $page }}</a>
      </li>
    @endforeach

      @if ($enrollments->hasMorePages())
      <li class="page-item">
      <a class="page-link" href="{{ $enrollments->nextPageUrl() }}">Next</a>
      </li>
    @else
      <li class="page-item disabled"><span class="page-link">Next</span></li>
    @endif
    </ul>
    </nav>
  </div>

  {{-- ENROLL MODAL --}}
  <div class="modal fade" id="enrollModal" tabindex="-1" aria-labelledby="enrollModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    <form action="{{ route('AdminComponents.enrollment') }}" method="POST">
      @csrf
      <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Enroll Student</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-start">
        {{-- Student --}}
        <div class="mb-3">
        <label class="form-label">Student</label>
        <select name="userID" class="form-control" required>
          <option value="">Select Student</option>
          @foreach($students as $student)
        <option value="{{ $student->id }}">
        {{ $student->lastName }}, {{ $student->firstName }}
        </option>
      @endforeach
        </select>
        </div>

        {{-- Grade Level --}}
        <div class="mb-3">
        <label class="form-label">Grade Level</label>
        <select name="gradeLevel" class="form-control" required>
          <option value="">Select Grade</option>
          <option value="Grade 11">Grade 11</option>
          <option value="Grade 12">Grade 12</option>
        </select>
        </div>

        {{-- Semester --}}
        <div class="mb-3">
        <label class="form-label">Semester</label>
        <select name="semester" class="form-control" required>
          <option value="Sem 1">Sem 1</option>
          <option value="Sem 2">Sem 2</option>
        </select>
        </div>

        {{-- Strand --}}
        <div class="mb-3">
        <label class="form-label">Strand</label>
        <select name="strandID" class="form-control" required>
          <option value="">Select Strand</option>
          @foreach($strands as $strand)
        <option value="{{ $strand->id }}">{{ $strand->strand }}</option>
      @endforeach
        </select>
        </div>

        {{-- Section (AJAX-populated based on strand and grade level) --}}
        <div class="mb-3">
        <label class="form-label">Section</label>
        <select name="sectionID" class="form-control" required>
          <option value="">Select Section</option>
        </select>
        </div>



        {{-- Hidden Input for School Year --}}
        <input type="hidden" name="schoolYearID" value="{{ $activeSchoolYear->id }}">
      </div>

      {{-- Modal Footer with Submit Button --}}
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary btn-sm">Enroll</button>
      </div>
      </div>
    </form>
    </div>
  </div>


  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="path/to/your/custom-script.js"></script>


  <script type="text/javascript">
    $(document).ready(function () {
    function updateSectionOptions(strandID, gradeLevel, semester, targetSelect) {
      if (strandID && gradeLevel && semester) {
      $.ajax({
        url: "{{ route('get.sections.by.strand.and.grade') }}",
        type: "GET",
        data: {
        strand_id: strandID,
        grade_level: gradeLevel,
        semester: semester
        },
        success: function (response) {
        targetSelect.empty().append('<option value="">Select Section</option>');
        if (response.length > 0) {
          response.forEach(function (section) {
          targetSelect.append('<option value="' + section.id + '">' + section.section + '</option>');
          });
        } else {
          targetSelect.append('<option value="">No sections available</option>');
        }
        }
      });
      }
    }

    // Enroll Modal — update sections when grade level, strand, or semester changes
    $('#enrollModal').on('change', 'select[name="strandID"], select[name="gradeLevel"], select[name="semester"]', function () {
      const strandID = $('#enrollModal select[name="strandID"]').val();
      const gradeLevel = $('#enrollModal select[name="gradeLevel"]').val();
      const semester = $('#enrollModal select[name="semester"]').val();
      const targetSelect = $('#enrollModal select[name="sectionID"]');

      updateSectionOptions(strandID, gradeLevel, semester, targetSelect);
    });

    // Update Modals — pre-fill section options when modal is shown
    $('[id^="updateEnrollmentModal"]').on('show.bs.modal', function (event) {
      const modal = $(this);
      const enrollmentID = modal.attr('id').replace('updateEnrollmentModal', '');

      const strandID = $('#strandID' + enrollmentID).val();
      const gradeLevel = $('#gradeLevel' + enrollmentID).val();
      const semester = $('#semester' + enrollmentID).val();
      const targetSelect = $('#sectionID' + enrollmentID);

      updateSectionOptions(strandID, gradeLevel, semester, targetSelect);
    });
    });
  </script>




@endsection
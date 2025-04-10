@extends('Dashboard.admin')

@section('title', 'UMIANKonek | Admin')

@section('content')
  <header class="text-center mb-2">
    <h1>STAFF MANAGEMENT</h1>
    <p>STUDENT</p>
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
    <form action="{{ route('AdminComponents.student') }}" method="GET">
      <div class="d-flex align-items-center">
      <input type="text" name="search" class="search-input" placeholder="Search..." value="{{ request('search') }}">
      <i class="fas fa-search search-icon"></i>
      </div>
    </form>
    </div>



    <!-- Button Group Aligned to End -->
    <div class="d-flex align-items-end ms-auto">
    <button type="button" class="btn btncustom mx-2" data-bs-toggle="modal" data-bs-target="#addUserModal">
      Add Section
    </button>
    <button type="button" class="btn btnarchive mx-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
      Archived
    </button>
    </div>
  </div>

  <div class="table-responsive ">
    <table class="table custom-table mt-2">
    <thead class="thead-dark">
      <tr>
      <th scope="col">ID</th>
      <th scope="col">Name</th>
      <th scope="col">Date Added</th>
      <th scope="col">Details</th>
      <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($users as $user)
      <tr>
      <th scope="row">{{ $user->id }}</th>
      <td>{{ $user->firstName }} {{ $user->middleName }} {{ $user->lastName }}</td>
      <td>{{ \Carbon\Carbon::parse($user->created_at)->format('F j, Y, h:i A') }}</td>
      <td>
      <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
      data-bs-target="#userDetailsModal{{ $user->id }}">See More</button>
      </td>
      <td class="d-flex justify-content-center gap-2">

      <form action="{{ route('student.archive', $user->id) }}" method="POST"
      onsubmit="return confirm('Are you sure you want to archive this user?');" style="display:inline;">
      @csrf
      <button type="submit" class="btn btn-info btn-sm">
        <i class="bi bi-file-earmark-zip-fill"></i>
      </button>
      </form>
      <button class="btn btn-primary btn-sm" onclick="if(confirm('Are you sure you want to edit this user?')) { 
      var myModal = new bootstrap.Modal(document.getElementById('editUserModal{{ $user->id }}')); 
      myModal.show(); 
      }">
      <i class="fa-solid fa-circle-info"></i>
      </button>


      <form action="{{ route('admin.destroy', $user->id) }}" method="POST"
      onsubmit="return confirm('Are you sure you want to delete this user?');" style="display:inline;">
      @csrf
      @method('DELETE')
      <button type="submit" class="btn btn-danger btn-sm">
        <i class="fa-solid fa-box-archive"></i>
      </button>
      </form>
      </td>
      </tr>


      <!-- Display -->
      <div class="modal fade" id="userDetailsModal{{ $user->id }}" tabindex="-1"
      aria-labelledby="userDetailsLabel{{ $user->id }}" aria-hidden="true">
      <div class="modal-dialog modal-lg">
      <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="userDetailsLabel{{ $user->id }}">User Details -
        {{ $user->getFullNameAttribute() }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul class="nav nav-tabs mb-3" id="detailsTab{{ $user->id }}" role="tablist">
        <li class="nav-item" role="presentation">
        <button class="nav-link active" id="user-tab{{ $user->id }}" data-bs-toggle="tab"
        data-bs-target="#userDetails{{ $user->id }}" type="button" role="tab">User Info</button>
        </li>
        <li class="nav-item" role="presentation">
        <button class="nav-link" id="address-tab{{ $user->id }}" data-bs-toggle="tab"
        data-bs-target="#addressDetails{{ $user->id }}" type="button" role="tab">Address Info</button>
        </li>
        <li class="nav-item" role="presentation">
        <button class="nav-link" id="guardian-tab{{ $user->id }}" data-bs-toggle="tab"
        data-bs-target="#guardianDetails{{ $user->id }}" type="button" role="tab">Guardian Info</button>
        </li>
        </ul>
        <div class="tab-content">
        <div class="tab-pane fade show active" id="userDetails{{ $user->id }}" role="tabpanel">
        <dl class="row">
        <dt class="col-sm-4">First Name:</dt>
        <dd class="col-sm-8">{{ $user->firstName }}</dd>

        <dt class="col-sm-4">Last Name:</dt>
        <dd class="col-sm-8">{{ $user->lastName }}</dd>

        <dt class="col-sm-4">Email:</dt>
        <dd class="col-sm-8">{{ $user->email }}</dd>

        <dt class="col-sm-4">Birthday:</dt>
        <dd class="col-sm-8">{{ $user->birthday }}</dd>

        <dt class="col-sm-4">Age:</dt>
        <dd class="col-sm-8">{{ $user->age }}</dd>

        <dt class="col-sm-4">Gender:</dt>
        <dd class="col-sm-8">{{ $user->gender }}</dd>

        <dt class="col-sm-4">Contact Number:</dt>
        <dd class="col-sm-8">{{ $user->contactNumber }}</dd>
        </dl>
        </div>

        <div class="tab-pane fade" id="addressDetails{{ $user->id }}" role="tabpanel">
        <dl class="row">
        <dt class="col-sm-4">Street:</dt>
        <dd class="col-sm-8">{{ $user->address->street ?? 'N/A' }}</dd>

        <dt class="col-sm-4">City:</dt>
        <dd class="col-sm-8">{{ $user->address->city ?? 'N/A' }}</dd>

        <dt class="col-sm-4">Province:</dt>
        <dd class="col-sm-8">{{ $user->address->province ?? 'N/A' }}</dd>

        <dt class="col-sm-4">Zip Code:</dt>
        <dd class="col-sm-8">{{ $user->address->zipcode ?? 'N/A' }}</dd>
        </dl>
        </div>

        <div class="tab-pane fade" id="guardianDetails{{ $user->id }}" role="tabpanel">
        <dl class="row">
        <dt class="col-sm-4">First Name:</dt>
        <dd class="col-sm-8">{{ $user->guardian->firstName ?? 'N/A' }}</dd>

        <dt class="col-sm-4">Middle Name:</dt>
        <dd class="col-sm-8">{{ $user->guardian->middleName ?? 'N/A' }}</dd>

        <dt class="col-sm-4">Last Name:</dt>
        <dd class="col-sm-8">{{ $user->guardian->lastName ?? 'N/A' }}</dd>

        <dt class="col-sm-4">Contact Number:</dt>
        <dd class="col-sm-8">{{ $user->guardian->contactNumber ?? 'N/A' }}</dd>
        </dl>
        </div>
        </div>
      </div>
      </div>
      </div>
      </div>

      <!-- UpdateModal -->
      <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1"
      aria-labelledby="editUserModalLabel{{ $user->id }}" aria-hidden="true">
      <div class="modal-dialog modal-lg">
      <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editUserModalLabel{{ $user->id }}">Edit User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-start">
        <form action="{{ route('admin.update', $user->id) }}" method="POST">
        @csrf

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" id="editUserTab{{ $user->id }}" role="tablist">
        <li class="nav-item" role="presentation">
        <button class="nav-link active" id="edit-user-tab{{ $user->id }}" data-bs-toggle="tab"
          data-bs-target="#edit-user{{ $user->id }}" type="button" role="tab">
          User Info
        </button>
        </li>
        <li class="nav-item" role="presentation">
        <button class="nav-link" id="edit-address-tab{{ $user->id }}" data-bs-toggle="tab"
          data-bs-target="#edit-address{{ $user->id }}" type="button" role="tab">
          Address
        </button>
        </li>
        <li class="nav-item" role="presentation">
        <button class="nav-link" id="edit-guardian-tab{{ $user->id }}" data-bs-toggle="tab"
          data-bs-target="#edit-guardian{{ $user->id }}" type="button" role="tab">
          Guardian
        </button>
        </li>
        <li class="nav-item" role="presentation">
        <button class="nav-link" id="edit-requirement-tab{{ $user->id }}" data-bs-toggle="tab"
          data-bs-target="#edit-requirement{{ $user->id }}" type="button" role="tab">
          Requirement
        </button>
        </li>
        </ul>

        <!-- Tab content -->
        <div class="tab-content mt-3">
        <!-- User Info Tab -->
        <div class="tab-pane fade show active" id="edit-user{{ $user->id }}" role="tabpanel">
        <div class="row">
          <div class="col">
          <div class="mb-3">
          <label class="form-label">First Name:</label>
          <input type="text" name="firstName" class="form-control" value="{{ $user->firstName }}"
          required>
          </div>
          <div class="mb-3">
          <label class="form-label">Middle Name: (Optional)</label>
          <input type="text" name="middleName" class="form-control" value="{{ $user->middleName }}">
          </div>
          <div class="mb-3">
          <label class="form-label">Last Name:</label>
          <input type="text" name="lastName" class="form-control" value="{{ $user->lastName }}"
          required>
          </div>
          <div class="mb-3">
          <label class="form-label">Email:</label>
          <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
          </div>

          </div>
          <div class="col">
          <div class="mb-3">
          <label class="form-label">Birthday:</label>
          <input type="date" name="birthday" class="form-control" value="{{ $user->birthday }}"
          required>
          </div>
          <div class="mb-3">
          <label class="form-label">Age:</label>
          <input type="number" name="age" class="form-control" value="{{ $user->age }}" required>
          </div>
          <div class="mb-3">
          <label class="form-label">Gender:</label>
          <select name="gender" class="form-select" required>
          <option value="Male" {{ $user->gender == 'Male' ? 'selected' : '' }}>Male</option>
          <option value="Female" {{ $user->gender == 'Female' ? 'selected' : '' }}>Female</option>
          <option value="Other" {{ $user->gender == 'Other' ? 'selected' : '' }}>Other</option>
          </select>
          </div>
          <div class="mb-3">
          <label class="form-label">Contact Number:</label>
          <input type="text" name="contactNumber" class="form-control"
          value="{{ $user->contactNumber }}" required>
          </div>
          </div>
        </div>
        </div>

        <!-- Address Tab -->
        <div class="tab-pane fade" id="edit-address{{ $user->id }}" role="tabpanel">
        <div class="mb-3">
          <label class="form-label">Street:</label>
          <input type="text" name="street" class="form-control" value="{{ $user->address->street }}"
          required>
        </div>
        <div class="mb-3">
          <label class="form-label">City:</label>
          <input type="text" name="city" class="form-control" value="{{ $user->address->city }}" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Province:</label>
          <input type="text" name="province" class="form-control" value="{{ $user->address->province }}"
          required>
        </div>
        <div class="mb-3">
          <label class="form-label">Zipcode:</label>
          <input type="text" name="zipcode" class="form-control" value="{{ $user->address->zipcode }}"
          required>
        </div>
        </div>

        <!-- Guardian Tab -->
        <div class="tab-pane fade" id="edit-guardian{{ $user->id }}" role="tabpanel">
        <div class="mb-3">
          <label class="form-label">First Name:</label>
          <input type="text" name="gfirstName" class="form-control" value="{{ $user->guardian->firstName }}"
          required>
        </div>
        <div class="mb-3">
          <label class="form-label">Middle Name:</label>
          <input type="text" name="gmiddleName" class="form-control"
          value="{{ $user->guardian->middleName }}" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Last Name:</label>
          <input type="text" name="glastName" class="form-control" value="{{ $user->guardian->lastName }}"
          required>
        </div>
        <div class="mb-3">
          <label class="form-label">Contact Number:</label>
          <input type="text" name="gcontactNumber" class="form-control"
          value="{{ $user->guardian->contactNumber }}" required>
        </div>
        </div>

        <!-- Requirements Tab -->
        <div class="tab-pane fade" id="edit-requirement{{ $user->id }}" role="tabpanel">

        <!-- Requirements Tab -->
        <div class="mb-3">
          <label class="form-label">Requirements:</label>
          @php
      $userRequirements = $user->studentRequirement->pluck('requirementID')->toArray() ?? [];
    @endphp
          <div class="form-check">
          <input class="form-check-input" type="checkbox" id="psd" name="requirements[]" value="1" {{ in_array(1, $userRequirements) ? 'checked' : '' }} required>
          <label class="form-check-label" for="psd">Personal Data Sheet (Required)</label>
          </div>
          <div class="form-check">
          <input class="form-check-input" type="checkbox" id="ef" name="requirements[]" value="2" {{ in_array(2, $userRequirements) ? 'checked' : '' }}>
          <label class="form-check-label" for="ef">Enrollment Form</label>
          </div>
          <div class="form-check">
          <input class="form-check-input" type="checkbox" id="gc" name="requirements[]" value="6" {{ in_array(6, $userRequirements) ? 'checked' : '' }} required>
          <label class="form-check-label" for="gc">Grade Card (Required)</label>
          </div>
          <div class="form-check">
          <input class="form-check-input" type="checkbox" id="f137" name="requirements[]" value="4" {{ in_array(4, $userRequirements) ? 'checked' : '' }}>
          <label class="form-check-label" for="f137">Form 137</label>
          </div>
          <div class="form-check">
          <input class="form-check-input" type="checkbox" id="nso" name="requirements[]" value="5" {{ in_array(5, $userRequirements) ? 'checked' : '' }} required>
          <label class="form-check-label" for="nso">Birth Certificate (Required)</label>
          </div>
          <div class="form-check">
          <input class="form-check-input" type="checkbox" id="gm" name="requirements[]" value="3" {{ in_array(3, $userRequirements) ? 'checked' : '' }} required>
          <label class="form-check-label" for="gm">Good Moral (Required)</label>
          </div>
          <div class="form-check">
          <input class="form-check-input" type="checkbox" id="ncae" name="requirements[]" value="7" {{ in_array(7, $userRequirements) ? 'checked' : '' }}>
          <label class="form-check-label" for="ncae">National Career Assessment Examination</label>
          </div>
        </div>
        <!-- Status -->
        <div class="mb-3">
          <label for="status" class="form-label">Status:</label>
          <select class="form-select" id="status" name="status" required>
          <option value="" disabled {{ empty($user->status) ? 'selected' : '' }}>Choose...</option>
          <option value="Highschool Graduate" {{ $user->status == 'Highschool Graduate' ? 'selected' : '' }}>Highschool Graduate</option>
          <option value="Grade 11" {{ $user->status == 'Grade 11' ? 'selected' : '' }}>Grade 11</option>
          <option value="Grade 12" {{ $user->status == 'Grade 12' ? 'selected' : '' }}>Grade 12</option>
          </select>
        </div>
        </div>

        <button type="submit" class="btn btn-success w-100 mt-3">Update</button>
        </form>
      </div>
      </div>
      </div>
      </div>



    @empty
      <tr>
      <td colspan="5">No Student available</td>
      </tr>
    @endforelse
    </tbody>
    </table>

    <!-- Hereee -->
    <nav aria-label="Page navigation example">
    <ul class="pagination">
      @if ($users->onFirstPage())
      <li class="page-item disabled"><span class="page-link">Previous</span></li>
    @else
      <li class="page-item">
      <a class="page-link" href="{{ $users->previousPageUrl() }}">Previous</a>
      </li>
    @endif

      @foreach ($users->links()->elements[0] as $page => $url)
      <li class="page-item {{ $users->currentPage() == $page ? 'active' : '' }}">
      <a class="page-link" href="{{ $url }}">{{ $page }}</a>
      </li>
    @endforeach

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

  <!-- Add User Modal -->
  <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- larger modal for tabs -->
    <div class="modal-content">
      <div class="modal-header">
      <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action="{{ route('AdminComponents.student') }}" method="POST">
        @csrf

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" id="addUserTab" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="user-tab" data-bs-toggle="tab" data-bs-target="#user" type="button"
          role="tab">User Info</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="address-tab" data-bs-toggle="tab" data-bs-target="#address" type="button"
          role="tab">Address</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="guardian-tab" data-bs-toggle="tab" data-bs-target="#guardian" type="button"
          role="tab">Guardian</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="requirement-tab" data-bs-toggle="tab" data-bs-target="#requirement"
          type="button" role="tab">Requirements</button>
        </li>
        </ul>

        <input type="hidden" name="roleID" value="3">
        <!-- Tab content -->
        <div class="tab-content mt-3 text-start">
        <!-- User Info Tab -->
        <div class="tab-pane fade show active" id="user" role="tabpanel">
          <div class="row">
          <div class="col">
            <div class="mb-3">
            <label for="firstName" class="form-label">First Name:</label>
            <input type="text" class="form-control" id="firstName" name="firstName" required>
            </div>
            <div class="mb-3">
            <label for="middleName" class="form-label">Middle Name: (Optional)</label>
            <input type="text" class="form-control" id="middleName" name="middleName">
            </div>
            <div class="mb-3">
            <label for="lastName" class="form-label">Last Name:</label>
            <input type="text" class="form-control" id="lastName" name="lastName" required>
            </div>
            <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
            </div>
          </div>
          <div class="col">
            <div class="mb-3">
            <label for="birthday" class="form-label">Birthday:</label>
            <input type="date" class="form-control" id="birthday" name="birthday" required>
            </div>
            <div class="mb-3">
            <label for="age" class="form-label">Age:</label>
            <input type="number" class="form-control" id="age" name="age" required>
            </div>
            <div class="mb-3">
            <label for="gender" class="form-label">Gender:</label>
            <select class="form-select" id="gender" name="gender" required>
              <option value="" selected disabled>Choose...</option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
              <option value="Other">Other</option>
            </select>
            </div>
            <div class="mb-3">
            <label for="contactNumber" class="form-label">Contact Number:</label>
            <input type="number" class="form-control" id="contactNumber" name="contactNumber" required>
            </div>
          </div>
          </div>
        </div>

        <!-- Address Tab -->
        <div class="tab-pane fade" id="address" role="tabpanel">
          <div class="mb-3">
          <label for="street" class="form-label">Street:</label>
          <input type="text" class="form-control" id="street" name="street" required>
          </div>
          <div class="mb-3">
          <label for="city" class="form-label">City:</label>
          <input type="text" class="form-control" id="city" name="city" required>
          </div>
          <div class="mb-3">
          <label for="province" class="form-label">Province:</label>
          <input type="text" class="form-control" id="province" name="province" required>
          </div>
          <div class="mb-3">
          <label for="zipcode" class="form-label">Zipcode:</label>
          <input type="text" class="form-control" id="zipcode" name="zipcode" required>
          </div>
        </div>

        <!-- Guardian Tab -->
        <div class="tab-pane fade" id="guardian" role="tabpanel">
          <div class="mb-3">
          <label for="fname" class="form-label">First Name:</label>
          <input type="text" class="form-control" id="fname" name="gfirstName" required>
          </div>
          <div class="mb-3">
          <label for="mname" class="form-label">Middle Name: (Optional)</label>
          <input type="text" class="form-control" id="mname" name="gmiddleName">
          </div>
          <div class="mb-3">
          <label for="lname" class="form-label">Last Name:</label>
          <input type="text" class="form-control" id="lname" name="glastName" required>
          </div>
          <div class="mb-3">
          <label for="cn" class="form-label">Contact Number:</label>
          <input type="text" class="form-control" id="cn" name="gcontactNumber" required>
          </div>
        </div>

        <!-- Requirements Tab -->
        <div class="tab-pane fade" id="requirement" role="tabpanel">

          <div class="mb-3">
          <label class="form-label">Requirements:</label>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="psd" name="psd" value="1" required>
            <label class="form-check-label" for="psd">Personal Data Sheet (Required)</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="ef" name="ef" value="2">
            <label class="form-check-label" for="ef">Enrollment Form</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="gc" name="gc" value="6" required>
            <label class="form-check-label" for="gc">Grade Card (Required)</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="f137" name="f137" value="4">
            <label class="form-check-label" for="f137">Form 137</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="nso" name="nso" value="5" required>
            <label class="form-check-label" for="nso">Birth Certificate (Required)</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="gm" name="gm" value="3" required>
            <label class="form-check-label" for="gm">Good Moral (Required)</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="ncae" name="ncae" value="7">
            <label class="form-check-label" for="ncae">National Career Assessment Examination</label>
          </div>
          </div>
        </div>

        <div class="mb-3">
          <label for="status" class="form-label">Status:</label>
          <select class="form-select" id="status" name="status" required>
          <option value="" selected disabled>Choose...</option>
          <option value="Highschool Graduate">Highschool Graduate</option>
          <option value="Grade 11">Grade 11</option>
          <option value="Grade 12">Grade 12</option>
          </select>
        </div>


        </div>

        <button type="submit" class="btn btn-success w-100 mt-3">Save</button>
      </form>
      </div>
    </div>
    </div>
  </div>


@endsection
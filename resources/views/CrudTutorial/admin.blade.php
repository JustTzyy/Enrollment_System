<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Management System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">



  <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
    <symbol id="check-circle-fill" viewBox="0 0 16 16">
      <path
        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
    </symbol>
    <symbol id="info-fill" viewBox="0 0 16 16">
      <path
        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
    </symbol>
    <symbol id="exclamation-triangle-fill" viewBox="0 0 16 16">
      <path
        d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
    </symbol>
  </svg>

  <style>
    body {
      background: linear-gradient(135deg, rgb(66, 72, 102), rgb(107, 154, 255));
      color: #333;
    }

    .container-fluid {
      display: flex;
      justify-content: center;
      align-items: start;
      height: 100vh;
      padding-top: 50px;
    }

    .col-10 {
      background: #fff;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    }

    .table {
      border-radius: 10px;
      overflow: hidden;
    }

    .table th {
      background-color: rgb(95, 131, 169);
      color: white;
    }

    .btn {
      border-radius: 5px;
      transition: all 0.3s ease;
    }

    .btn:hover {
      opacity: 0.8;
    }

    .modal-content {
      border-radius: 10px;
      box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    }
  </style>
</head>

<body>
  <div class="container-fluid">
    <div class="col-10">
      <h1 class="text-start mb-4">Admin</h1>

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


      <div class="text-end mb-3">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal">
          <i class="bi bi-file-plus-fill"></i> Add User
        </button>
      </div>

      <div class="table-responsive">
        <table class="table table-bordered text-center">
          <thead>
            <tr>
              <th>ID</th>
              <th>Full Name</th>
              <th>Status</th>
              <th>Details</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($users as $user)
              <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->firstName }} {{ $user->middleName }} {{ $user->lastName }}</td>
                <td>{{ ucfirst($user->status) }}</td>
                <td>
                <!-- Example detail button or info -->
                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                  data-bs-target="#userDetailsModal{{ $user->id }}">See More</button>
                </td>
                <td>
                <!-- Example edit or delete buttons -->
                <button class="btn btn-warning btn-sm" onclick="if(confirm('Are you sure you want to edit this user?')) { 
          var myModal = new bootstrap.Modal(document.getElementById('editUserModal{{ $user->id }}')); 
          myModal.show(); 
          }">
                  <i class="fa-solid fa-pen"></i>
                </button>

                <form action="{{ route('admin.destroy', $user->id) }}" method="POST"
                  onsubmit="return confirm('Are you sure you want to delete this user?');" style="display:inline;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm">
                  <i class="bi bi-trash"></i>
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
                  <div class="modal-body">
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
                    </ul>

                    <!-- Tab content -->
                    <div class="tab-content mt-3">
                    <!-- User Info Tab -->
                    <div class="tab-pane fade show active" id="edit-user{{ $user->id }}" role="tabpanel">
                      <div class="row">
                      <div class="col">
                        <div class="mb-3">
                        <label class="form-label">First Name</label>
                        <input type="text" name="firstName" class="form-control" value="{{ $user->firstName }}"
                          required>
                        </div>
                        <div class="mb-3">
                        <label class="form-label">Middle Name</label>
                        <input type="text" name="middleName" class="form-control"
                          value="{{ $user->middleName }}">
                        </div>
                        <div class="mb-3">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="lastName" class="form-control" value="{{ $user->lastName }}"
                          required>
                        </div>
                        <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}"
                          required>
                        </div>

                      </div>
                      <div class="col">
                        <div class="mb-3">
                        <label class="form-label">Birthday</label>
                        <input type="date" name="birthday" class="form-control" value="{{ $user->birthday }}"
                          required>
                        </div>
                        <div class="mb-3">
                        <label class="form-label">Age</label>
                        <input type="number" name="age" class="form-control" value="{{ $user->age }}" required>
                        </div>
                        <div class="mb-3">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-select" required>
                          <option value="Male" {{ $user->gender == 'Male' ? 'selected' : '' }}>Male</option>
                          <option value="Female" {{ $user->gender == 'Female' ? 'selected' : '' }}>Female</option>
                          <option value="Other" {{ $user->gender == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        </div>
                        <div class="mb-3">
                        <label class="form-label">Contact Number</label>
                        <input type="text" name="contactNumber" class="form-control"
                          value="{{ $user->contactNumber }}" required>
                        </div>
                      </div>
                      </div>
                    </div>

                    <!-- Address Tab -->
                    <div class="tab-pane fade" id="edit-address{{ $user->id }}" role="tabpanel">
                      <div class="mb-3">
                      <label class="form-label">Street</label>
                      <input type="text" name="street" class="form-control" value="{{ $user->address->street }}" required>
                      </div>
                      <div class="mb-3">
                      <label class="form-label">City</label>
                      <input type="text" name="city" class="form-control" value="{{ $user->address->city }}" required>
                      </div>
                      <div class="mb-3">
                      <label class="form-label">Province</label>
                      <input type="text" name="province" class="form-control" value="{{ $user->address->province }}" required>
                      </div>
                      <div class="mb-3">
                      <label class="form-label">Zipcode</label>
                      <input type="text" name="zipcode" class="form-control" value="{{ $user->address->zipcode }}" required>
                      </div>
                    </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-3">Update</button>
                  </form>
                  </div>
                </div>
                </div>
              </div>



      @empty


    <tr>
      <td colspan="5">No users with roleID 1 available</td>
    </tr>
  @endforelse
          </tbody>
        </table>
      </div>
    </div>
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
          <form action="{{ route('admin') }}" method="POST">
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
            </ul>
                
            <input type="hidden" name="roleID" value="1">
            <!-- Tab content -->
            <div class="tab-content mt-3">
              <!-- User Info Tab -->
              <div class="tab-pane fade show active" id="user" role="tabpanel">
                <div class="row">
                  <div class="col">
                    <div class="mb-3">
                      <label for="firstName" class="form-label">First Name</label>
                      <input type="text" class="form-control" id="firstName" name="firstName" required>
                    </div>
                    <div class="mb-3">
                      <label for="middleName" class="form-label">Middle Name</label>
                      <input type="text" class="form-control" id="middleName" name="middleName">
                    </div>
                    <div class="mb-3">
                      <label for="lastName" class="form-label">Last Name</label>
                      <input type="text" class="form-control" id="lastName" name="lastName" required>
                    </div>
                    <div class="mb-3">
                      <label for="email" class="form-label">Email</label>
                      <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                  </div>
                  <div class="col">
                    <div class="mb-3">
                      <label for="birthday" class="form-label">Birthday</label>
                      <input type="date" class="form-control" id="birthday" name="birthday" required>
                    </div>
                    <div class="mb-3">
                      <label for="age" class="form-label">Age</label>
                      <input type="number" class="form-control" id="age" name="age" required>
                    </div>
                    <div class="mb-3">
                      <label for="gender" class="form-label">Gender</label>
                      <select class="form-select" id="gender" name="gender" required>
                        <option value="" selected disabled>Choose...</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                      </select>
                    </div>
                    <div class="mb-3">
                      <label for="contactNumber" class="form-label">Contact Number</label>
                      <input type="number" class="form-control" id="contactNumber" name="contactNumber" required>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Address Tab -->
              <div class="tab-pane fade" id="address" role="tabpanel">
                <div class="mb-3">
                  <label for="street" class="form-label">Street</label>
                  <input type="text" class="form-control" id="street" name="street" required>
                </div>
                <div class="mb-3">
                  <label for="city" class="form-label">City</label>
                  <input type="text" class="form-control" id="city" name="city" required>
                </div>
                <div class="mb-3">
                  <label for="province" class="form-label">Province</label>
                  <input type="text" class="form-control" id="province" name="province" required>
                </div>
                <div class="mb-3">
                  <label for="zipcode" class="form-label">Zipcode</label>
                  <input type="text" class="form-control" id="zipcode" name="zipcode" required>
                </div>
              </div>
            </div>

            <button type="submit" class="btn btn-success w-100 mt-3">Save</button>
          </form>
        </div>
      </div>
    </div>
  </div>



  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
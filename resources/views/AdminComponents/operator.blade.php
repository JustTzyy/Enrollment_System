@extends('Dashboard.admin')

@section('title', 'UMIANKonek | Admin')

@section('content')
    <header class="text-center mb-2">
        <h1>STAFF MANAGEMENT</h1>
        <p>OPERATORS</p>
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
            <form action="{{ route('AdminComponents.operator') }}" method="GET">
                <div class="d-flex align-items-center">
                    <input type="text" name="search" class="search-input" placeholder="Search..."
                        value="{{ request('search') }}">
                    <i class="fas fa-search search-icon"></i>
                </div>
            </form>
        </div>



        <!-- Button Group Aligned to End -->
        <div class="d-flex align-items-end ms-auto">
            <button type="button" class="btn btncustom mx-2" data-bs-toggle="modal" data-bs-target="#addUserModal">
                Add Operator
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
                            <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal"
                                data-bs-target="#userDetailsModal{{ $user->id }}">
                                <i class="fas fa-eye me-1"></i> See More
                            </button>
                        </td>
                        <td class="d-flex justify-content-center gap-2">
                            <button class="btn btn-outline-primary btn-sm d-flex align-items-center"
                                onclick="if(confirm('Are you sure you want to edit this user?')) { var myModal = new bootstrap.Modal(document.getElementById('editUserModal{{ $user->id }}')); myModal.show(); }">
                                <i class="fas fa-pen me-1"></i> Edit
                            </button>
                            <form action="{{ route('admin.destroy', $user->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this user?');"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm d-flex align-items-center">
                                    <i class="fas fa-trash me-1"></i> Delete
                                </button>
                        </td>
                    </tr>


                    <!-- Display -->
                    <div class="modal fade" id="userDetailsModal{{ $user->id }}" tabindex="-1"
                        aria-labelledby="userDetailsLabel{{ $user->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="userDetailsLabel{{ $user->id }}">
                                        <i class="fas fa-user-shield me-2"></i>Admin Details -
                                        {{ $user->getFullNameAttribute() }}
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-start">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-body">
                                            <ul class="nav nav-tabs mb-3" id="detailsTab{{ $user->id }}" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link active" id="user-tab{{ $user->id }}"
                                                        data-bs-toggle="tab" data-bs-target="#userDetails{{ $user->id }}"
                                                        type="button" role="tab">
                                                        <i class="fas fa-user me-1"></i> User Info
                                                    </button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="address-tab{{ $user->id }}"
                                                        data-bs-toggle="tab" data-bs-target="#addressDetails{{ $user->id }}"
                                                        type="button" role="tab">
                                                        <i class="fas fa-map-marker-alt me-1"></i> Address Info
                                                    </button>
                                                </li>
                                            </ul>
                                            <div class="tab-content">
                                                <div class="tab-pane fade show active" id="userDetails{{ $user->id }}"
                                                    role="tabpanel">
                                                    <div class="mb-3">
                                                        <h6 class="fw-bold mb-3 text-primary"><i
                                                                class="fas fa-id-badge me-2"></i>Personal Information</h6>
                                                        <dl class="row mb-0">
                                                            <dt class="col-sm-4">First Name:</dt>
                                                            <dd class="col-sm-8">{{ $user->firstName }}</dd>
                                                            <dt class="col-sm-4">Middle Name:</dt>
                                                            <dd class="col-sm-8">{{ $user->middleName }}</dd>
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
                                                </div>
                                                <div class="tab-pane fade" id="addressDetails{{ $user->id }}" role="tabpanel">
                                                    <div class="mb-3">
                                                        <h6 class="fw-bold mb-3 text-primary"><i
                                                                class="fas fa-home me-2"></i>Address Information</h6>
                                                        <dl class="row mb-0">
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
                                    <form action="{{ route('operator.update', $user->id) }}" method="POST">
                                        @csrf

                                        <!-- Nav tabs -->
                                        <ul class="nav nav-tabs" id="editUserTab{{ $user->id }}" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link active" id="edit-user-tab{{ $user->id }}"
                                                    data-bs-toggle="tab" data-bs-target="#edit-user{{ $user->id }}"
                                                    type="button" role="tab">
                                                    User Info
                                                </button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="edit-address-tab{{ $user->id }}"
                                                    data-bs-toggle="tab" data-bs-target="#edit-address{{ $user->id }}"
                                                    type="button" role="tab">
                                                    Address
                                                </button>
                                            </li>
                                        </ul>

                                        <!-- Tab content -->
                                        <div class="tab-content mt-3">
                                            <!-- User Info Tab -->
                                            <div class="tab-pane fade show active" id="edit-user{{ $user->id }}"
                                                role="tabpanel">
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="mb-3">
                                                            <label class="form-label">First Name:</label>
                                                            <input type="text" name="firstName" class="form-control"
                                                                value="{{ $user->firstName }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Middle Name: (Optional)</label>
                                                            <input type="text" name="middleName" class="form-control"
                                                                value="{{ $user->middleName }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Last Name:</label>
                                                            <input type="text" name="lastName" class="form-control"
                                                                value="{{ $user->lastName }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Email:</label>
                                                            <input type="email" name="email" class="form-control"
                                                                value="{{ $user->email }}" required>
                                                        </div>

                                                    </div>
                                                    <div class="col">
                                                        <div class="mb-3">
                                                            <label class="form-label">Birthday:</label>
                                                            <input type="date" name="birthday" class="form-control"
                                                                value="{{ $user->birthday }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Age:</label>
                                                            <input type="number" name="age" class="form-control"
                                                                value="{{ $user->age }}" required>
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
                                                    <input type="text" name="street" class="form-control"
                                                        value="{{ $user->address->street }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">City:</label>
                                                    <input type="text" name="city" class="form-control"
                                                        value="{{ $user->address->city }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Province:</label>
                                                    <input type="text" name="province" class="form-control"
                                                        value="{{ $user->address->province }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Zipcode:</label>
                                                    <input type="text" name="zipcode" class="form-control"
                                                        value="{{ $user->address->zipcode }}" required>
                                                </div>
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
                        <td colspan="5">No Operator available</td>
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
                    <form action="{{ route('AdminComponents.operator') }}" method="POST">
                        @csrf

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" id="addUserTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="user-tab" data-bs-toggle="tab" data-bs-target="#user"
                                    type="button" role="tab">User Info</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="address-tab" data-bs-toggle="tab" data-bs-target="#address"
                                    type="button" role="tab">Address</button>
                            </li>
                        </ul>

                        <input type="hidden" name="roleID" value="4">
                        <input type="hidden" name="status" value="Active">

                        <!-- Tab content -->
                        <div class="tab-content mt-3 text-start">
                            <!-- User Info Tab -->
                            <div class="tab-pane fade show active" id="user" role="tabpanel">
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label for="firstName" class="form-label">First Name:</label>
                                            <input type="text" class="form-control" id="firstName" name="firstName"
                                                required>
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
                                            <input type="number" class="form-control" id="contactNumber"
                                                name="contactNumber" required>
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
                        </div>

                        <button type="submit" class="btn btn-success w-100 mt-3">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection
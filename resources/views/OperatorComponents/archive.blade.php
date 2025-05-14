@extends('Dashboard.operator')

@section('title', 'UMIANKonek | Operator')

@section('operatorcontent')
  <header class="text-center mb-4">
    <h1 class="fw-bold">STAFF MANAGEMENT</h1>
    <p class="text-muted">STUDENT ARCHIVE</p>
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

  {{-- SEARCH --}}
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div class="search-container">
      <form action="{{ route('OperatorComponents.archive') }}" method="GET">
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
              <button type="button" class="btn btn-outline-info btn-sm " data-bs-toggle="modal"
                data-bs-target="#userDetailsModal{{ $user->id }}">
                <i class="fas fa-eye me-1"></i> See More
              </button>
            </td>
            <td>
              <form action="{{ route('archive.archive', $user->id) }}" method="POST"
                onsubmit="return confirm('Are you sure you want to unarchive this user?');" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-outline-success btn-sm ">
                  <i class="fas fa-archive me-1"></i> Unarchive
                </button>
              </form>
            </td>
          </tr>

          <!-- User Details Modal (See More) -->
          <div class="modal fade" id="userDetailsModal{{ $user->id }}" tabindex="-1"
            aria-labelledby="userDetailsLabel{{ $user->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                  <h5 class="modal-title" id="userDetailsLabel{{ $user->id }}">
                    <i class="fas fa-user-graduate me-2"></i>Student Details - {{ $user->getFullNameAttribute() }}
                  </h5>
                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-start">
                  <div class="card border-0 shadow-sm">
                    <div class="card-body">
                      <ul class="nav nav-tabs mb-3" id="detailsTab{{ $user->id }}" role="tablist">
                        <li class="nav-item" role="presentation">
                          <button class="nav-link active text-primary" id="user-tab{{ $user->id }}" data-bs-toggle="tab" data-bs-target="#userDetails{{ $user->id }}" type="button" role="tab">
                            <i class="fas fa-user me-1 text-primary"></i> User Info
                          </button>
                        </li>
                        <li class="nav-item" role="presentation">
                          <button class="nav-link text-info" id="address-tab{{ $user->id }}" data-bs-toggle="tab" data-bs-target="#addressDetails{{ $user->id }}" type="button" role="tab">
                            <i class="fas fa-map-marker-alt me-1 text-info"></i> Address Info
                          </button>
                        </li>
                        <li class="nav-item" role="presentation">
                          <button class="nav-link text-success" id="guardian-tab{{ $user->id }}" data-bs-toggle="tab" data-bs-target="#guardianDetails{{ $user->id }}" type="button" role="tab">
                            <i class="fas fa-user-friends me-1 text-success"></i> Guardian Info
                          </button>
                        </li>
                      </ul>
                      <div class="tab-content">
                        <div class="tab-pane fade show active" id="userDetails{{ $user->id }}" role="tabpanel">
                          <div class="mb-3">
                            <h6 class="fw-bold mb-3 text-primary"><i class="fas fa-id-badge me-2 text-primary"></i>Personal Information</h6>
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
                            <h6 class="fw-bold mb-3 text-info"><i class="fas fa-home me-2 text-info"></i>Address Information</h6>
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
                        <div class="tab-pane fade" id="guardianDetails{{ $user->id }}" role="tabpanel">
                          <div class="mb-3">
                            <h6 class="fw-bold mb-3 text-success"><i class="fas fa-user-friends me-2 text-success"></i>Guardian Information</h6>
                            <dl class="row mb-0">
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
              </div>
            </div>
          </div>
        @empty
          <tr>
            <td colspan="5">
              No archived users found.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>

    <!-- Pagination -->
    <nav aria-label="Page navigation example">
      <ul class="pagination justify-content-start">
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

  <style>
    .custom-table {
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }
    
    .custom-table thead th {
      font-weight: 600;
      padding: 12px;
      border: none;
    }
    
    .custom-table tbody td {
      padding: 12px;
      vertical-align: middle;
    }
    
    .dropdown-menu {
      border: none;
      border-radius: 8px;
      padding: 8px;
    }
    
    .dropdown-item {
      border-radius: 4px;
      padding: 8px 16px;
    }
    
    .dropdown-item:hover {
      background-color: #f8f9fa;
    }
  </style>
@endsection

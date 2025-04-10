@extends('Dashboard.admin')

@section('title', 'UMIANKonek | Admin')

@section('content')
    <header class="text-center mb-4">
        <h1>SETTINGS</h1>
        <p>ACCOUNT SETTING</p>
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

    <ul class="nav nav-tabs mb-4 justify-content-start" id="settingsTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="user-tab" data-bs-toggle="tab" data-bs-target="#user" type="button"
                role="tab">User Info</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="address-tab" data-bs-toggle="tab" data-bs-target="#address" type="button"
                role="tab">Address</button>
        </li>
    </ul>

    <div class="tab-content px-5" id="settingsTabsContent">
        <!-- User Info Tab -->
        <div class="tab-pane fade show active" id="user" role="tabpanel">
            <form class="row g-3" method="POST" action="{{ route('setting.updateUser') }}">
                @csrf
                <div class="col-md-4">
                    <label class="form-label">First Name</label>
                    <input type="text" class="form-control" name="firstName" value="{{ $user->firstName ?? '' }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Middle Name</label>
                    <input type="text" class="form-control" name="middleName" value="{{ $user->middleName ?? '' }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Last Name</label>
                    <input type="text" class="form-control" name="lastName" value="{{ $user->lastName ?? '' }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Age</label>
                    <input type="number" class="form-control" name="age" value="{{ $user->age ?? '' }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Birthday</label>
                    <input type="date" class="form-control" name="birthday" value="{{ $user->birthday ?? '' }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Gender</label>
                    <select class="form-select" name="gender">
                        <option disabled {{ $user->gender == null ? 'selected' : '' }}>Choose...</option>
                        <option value="Male" {{ $user->gender == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ $user->gender == 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ $user->gender == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>

                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="{{ $user->email ?? '' }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Contact Number</label>
                    <input type="text" class="form-control" name="contactNumber" value="{{ $user->contactNumber ?? '' }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" name="name" value="{{ $user->name ?? '' }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-success w-100">Save User Info</button>
                </div>
            </form>
        </div>

        <!-- Address Tab -->
        <div class="tab-pane fade" id="address" role="tabpanel">
            <form class="row g-3" method="POST" action="{{ route('setting.updateAddress') }}">
                @csrf
                <div class="col-md-6">
                    <label class="form-label">Street</label>
                    <input type="text" class="form-control" name="street" value="{{ $address->street ?? '' }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">City</label>
                    <input type="text" class="form-control" name="city" value="{{ $address->city ?? '' }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Province</label>
                    <input type="text" class="form-control" name="province" value="{{ $address->province ?? '' }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Zip Code</label>
                    <input type="text" class="form-control" name="zipcode" value="{{ $address->zipcode ?? '' }}">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-success w-100">Save Address</button>
                </div>
            </form>
        </div>
    </div>
@endsection
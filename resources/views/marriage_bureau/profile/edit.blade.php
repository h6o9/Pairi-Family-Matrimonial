@extends('marriage_bureau.master_layout')
@section('title')
<title>Edit Profile - Piyari Family</title>
@endsection
@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Edit Profile</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('marriage-bureau.subscription.index') }}">Dashboard</a></div>
                <div class="breadcrumb-item active">Edit Profile</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header"><h4>Profile Information</h4></div>
                        <div class="card-body">
                            <form action="{{ route('marriage-bureau.profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="form-group text-center mb-4">
                                    <img src="{{ $bureau->image ? asset($bureau->image) : asset('backend/img/avatar/avatar-1.png') }}" alt="Profile" class="rounded-circle mb-2" width="100" height="100" style="object-fit:cover;">
                                    <div>
                                        <label class="btn btn-sm btn-primary mt-2">
                                            Update Image
                                            <input type="file" name="image" class="d-none" accept="image/*">
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Name *</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $bureau->name) }}" required>
                                </div>

                                <div class="form-group">
                                    <label>Email *</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', $bureau->email) }}" required>
                                </div>

                                <div class="form-group">
                                    <label>Phone *</label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $bureau->phone) }}" required>
                                </div>

                                <button type="submit" class="btn btn-primary">Update Profile</button>
                            </form>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header"><h4>Change Password</h4></div>
                        <div class="card-body">
                            <form action="{{ route('marriage-bureau.profile.password') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label>Current Password *</label>
                                    <input type="password" name="current_password" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label>New Password *</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label>Confirm Password *</label>
                                    <input type="password" name="password_confirmation" class="form-control" required>
                                </div>

                                <button type="submit" class="btn btn-primary">Update Password</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

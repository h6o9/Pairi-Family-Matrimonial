@extends('marriage_bureau.master_layout')
@section('title')
<title>Manage Users - Piyari Family</title>
@endsection
@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Manage Users</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('marriage-bureau.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Users</div>
            </div>
        </div>

        <div class="section-body">
            <a href="{{ route('marriage-bureau.users.create') }}" class="btn btn-primary mb-4"><i class="fas fa-plus"></i> Create New User</a>
            <p class="text-muted">Profiles created here appear as regular profiles on the Piyari Family app (visible in search & matches), but cannot be used to log in to the app.</p>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped data-table" id="mbUsersTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Photo</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Gender / Age</th>
                                    <th>City</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if($user->profile_photo)
                                            <img src="{{ $user->profile_photo }}" style="width:40px;height:40px;object-fit:cover;" class="rounded-circle">
                                        @else
                                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                                <i class="fas fa-user text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td><a href="{{ route('marriage-bureau.users.show', $user->id) }}">{{ $user->name }}</a></td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td>{{ ucfirst($user->gender ?? '-') }}{{ $user->age ? ' / '.$user->age : '' }}</td>
                                    <td>{{ $user->city ?? '-' }}</td>
                                    <td><span class="badge badge-success">Live on App</span></td>
                                    <td>
                                        <div class="table-actions">
                                            <a href="{{ route('marriage-bureau.users.show', $user->id) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> View</a>
                                            <a href="{{ route('marriage-bureau.users.edit', $user->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
                                            <x-admin.delete-button class="deleteForm" data-url="{{ route('marriage-bureau.users.destroy', $user->id) }}" text="Delete" />
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<x-admin.delete-modal />
@endsection

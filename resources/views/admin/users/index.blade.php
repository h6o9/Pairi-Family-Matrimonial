@extends('admin.master_layout')
@section('title')
    <title>Users - Pairi Family</title>
@endsection
@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Users Management</h1>
        </div>
        <div class="section-body">
            <div class="card mb-3">
                <div class="card-body">
                    <form method="GET" class="row">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" placeholder="Search name, email, phone, city..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="verified" class="form-control">
                                <option value="">Email Verified</option>
                                <option value="yes" @selected(request('verified') === 'yes')>Verified</option>
                                <option value="no" @selected(request('verified') === 'no')>Pending</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="phone_verified" class="form-control">
                                <option value="">Phone Verified</option>
                                <option value="yes" @selected(request('phone_verified') === 'yes')>Verified</option>
                                <option value="no" @selected(request('phone_verified') === 'no')>Pending</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="profile" class="form-control">
                                <option value="">Profile Status</option>
                                <option value="complete" @selected(request('profile') === 'complete')>Complete</option>
                                <option value="incomplete" @selected(request('profile') === 'incomplete')>Incomplete</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-control">
                                <option value="">Account Status</option>
                                <option value="active" @selected(request('status') === 'active')>Active</option>
                                <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-primary btn-block" type="submit"><i class="fa fa-search"></i></button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Age</th>
                                <th>Location</th>
                                <th>Profession</th>
                                <th>Email / Phone</th>
                                <th>Verified</th>
                                <th>Profile</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>
                                    <strong>{{ $user->name }}</strong>
                                    @if($user->gender)
                                        <br><small class="text-muted">{{ ucfirst($user->gender) }}</small>
                                    @endif
                                </td>
                                <td>{{ $user->age ?? '-' }}</td>
                                <td>{{ trim(implode(', ', array_filter([$user->city, $user->country]))) ?: '-' }}</td>
                                <td>{{ $user->job_title ?? '-' }}</td>
                                <td>
                                    <small>{{ $user->email }}</small><br>
                                    <small>{{ $user->phone ?? '-' }}</small>
                                </td>
                                <td>
                                    @if($user->is_verified)
                                        <span class="badge badge-success">Email</span>
                                    @else
                                        <span class="badge badge-warning">Email</span>
                                    @endif
                                    @if($user->phone_verified)
                                        <span class="badge badge-info">Phone</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-{{ $user->profile_completed ? 'success' : 'secondary' }}">
                                        {{ $user->profile_completed ? 'Complete' : 'Step ' . $user->profile_step . '/8' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $user->status === 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-info btn-sm" title="View"><i class="fa fa-eye"></i></a>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this user?')" title="Delete"><i class="fa fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">No users found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

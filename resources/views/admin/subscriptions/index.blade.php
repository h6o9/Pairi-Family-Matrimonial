@extends('admin.master_layout')
@section('title')
    <title>Subscriptions - Pairi Family</title>
@endsection
@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header d-flex justify-content-between align-items-center">
            <h1>Subscriptions Management</h1>
            <a href="{{ route('admin.subscriptions.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Subscription</a>
        </div>
        <div class="section-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card">
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Price</th>
                                <th>Duration</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subscriptions as $sub)
                            <tr>
                                <td>{{ $sub->id }}</td>
                                <td><strong>{{ $sub->name }}</strong></td>
                                <td>{{ $sub->type }}</td>
                                <td>PKR {{ number_format($sub->price, 2) }}</td>
                                <td>{{ $sub->duration_days }} Days</td>
                                <td>
                                    <span class="badge badge-{{ $sub->status === 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($sub->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.subscriptions.edit', $sub->id) }}" class="btn btn-info btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                                    <form action="{{ route('admin.subscriptions.destroy', $sub->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this plan?')" title="Delete"><i class="fa fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No subscriptions found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

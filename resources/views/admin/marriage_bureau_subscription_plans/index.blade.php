@extends('admin.master_layout')

@section('title')
<title>MB Subscription Plans - Piyari Family</title>
@stop

@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Marriage Bureau Subscription Plans</h1>
        </div>

        <div class="section-body">
            <a href="{{ route('admin.marriage-bureau-subscriptions.create') }}" class="btn btn-primary mb-4"><i class="fas fa-plus"></i> Create New Plan</a>
            
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($plans as $plan)
                                <tr>
                                    <td>{{ $plan->id }}</td>
                                    <td>{{ $plan->name }}</td>
                                    <td>PKR {{ number_format($plan->price) }}</td>
                                    <td>
                                        @if($plan->status == 'active')
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.marriage-bureau-subscriptions.edit', $plan->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('admin.marriage-bureau-subscriptions.destroy', $plan->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-danger">No subscription plans found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $plans->links() }}
                </div>
            </div>
        </div>
    </section>
</div>
@stop
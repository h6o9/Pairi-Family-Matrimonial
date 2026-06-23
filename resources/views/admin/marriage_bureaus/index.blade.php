@extends('admin.master_layout')

@section('title')
<title>Marriage Bureaus - Piyari Family</title>
@endsection

@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Marriage Bureaus</h1>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Joined At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bureaus as $bureau)
                                <tr>
                                    <td>{{ $bureau->id }}</td>
                                    <td>{{ $bureau->name }}</td>
                                    <td>{{ $bureau->email }}</td>
                                    <td>
                                        @if($bureau->status == 'active')
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ $bureau->created_at->format('d M, Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.marriage-bureaus.show', $bureau->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> View</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-danger">No marriage bureaus found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $bureaus->links() }}
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

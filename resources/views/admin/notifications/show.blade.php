@extends('admin.master_layout')
@section('title')
    <title>Notification Details - Piyari Family</title>
@endsection
@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ route('admin.notifications.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Notification Details</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Title:</strong> {{ $notification->title }}</p>
                            <p><strong>Send To:</strong>
                                <span class="badge badge-{{ $notification->send_to === 'all' ? 'info' : 'primary' }}">
                                    {{ $notification->send_to === 'all' ? 'All Users' : 'Selected Users' }}
                                </span>
                            </p>
                            <p><strong>Recipients:</strong> {{ $notification->recipient_count }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Created By:</strong> {{ $notification->creator->name ?? 'Admin' }}</p>
                            <p><strong>Date:</strong> {{ $notification->created_at?->format('d M Y, h:i A') }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><strong>Message</strong></label>
                        <div class="p-3 border rounded bg-light">{{ $notification->message }}</div>
                    </div>

                    @if($notification->send_to === 'selected' && $notification->userNotifications->isNotEmpty())
                        <hr>
                        <h5>Recipients</h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Read</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($notification->userNotifications as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->user->name ?? '-' }}</td>
                                            <td>{{ $item->user->email ?? '-' }}</td>
                                            <td>
                                                @if($item->is_read)
                                                    <span class="badge badge-success">Read</span>
                                                @else
                                                    <span class="badge badge-warning">Unread</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <div class="mt-3">
                        <x-admin.delete-button class="deleteForm btn btn-danger" data-url="{{ route('admin.notifications.destroy', $notification->id) }}" title="Delete from Admin" />
                        <small class="text-muted d-block mt-2">Deleting here removes it from admin panel only. Users keep it until they clear notifications from the app.</small>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<x-admin.delete-modal />
@endsection

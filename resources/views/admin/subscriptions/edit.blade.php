@extends('admin.master_layout')
@section('title')
    <title>Edit Subscription - Pairi Family</title>
@endsection
@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Edit Subscription</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.subscriptions.update', $subscription->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Plan Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $subscription->name }}" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Price (PKR)</label>
                                <input type="number" step="0.01" name="price" class="form-control" value="{{ $subscription->price }}" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Duration (Days)</label>
                                <input type="number" name="duration_days" class="form-control" value="{{ $subscription->duration_days }}" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Type</label>
                                <select name="type" class="form-control">
                                    <option value="VIP" @selected($subscription->type === 'VIP')>VIP</option>
                                    <option value="VVIP" @selected($subscription->type === 'VVIP')>VVIP</option>
                                    <option value="Free" @selected($subscription->type === 'Free')>Free</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="active" @selected($subscription->status === 'active')>Active</option>
                                    <option value="inactive" @selected($subscription->status === 'inactive')>Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-primary">Update Subscription</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

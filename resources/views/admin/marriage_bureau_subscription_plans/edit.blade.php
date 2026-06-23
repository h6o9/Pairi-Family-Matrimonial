@extends('admin.master_layout')
@section('title')
<title>Edit MB Subscription Plan - Piyari Family</title>
@endsection
@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Edit MB Subscription Plan</h1>
        </div>

        <div class="section-body">
            <a href="{{ route('admin.marriage-bureau-subscriptions.index') }}" class="btn btn-primary mb-4"><i class="fas fa-arrow-left"></i> Back to Plans</a>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.marriage-bureau-subscriptions.update', $marriage_bureau_subscription->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Plan Name</label>
                                <input type="text" class="form-control" name="name" value="{{ $marriage_bureau_subscription->name }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Price (PKR)</label>
                                <input type="number" class="form-control" name="price" value="{{ $marriage_bureau_subscription->price }}" required min="0">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Payment Status</label>
                            <select class="form-control" name="payment_status" required>
                                <option value="free" {{ $marriage_bureau_subscription->payment_status == 'free' ? 'selected' : '' }}>Free</option>
                                <option value="paid" {{ $marriage_bureau_subscription->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control" name="description" rows="4">{{ $marriage_bureau_subscription->description }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" name="status" required>
                                <option value="active" {{ $marriage_bureau_subscription->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $marriage_bureau_subscription->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Update Plan</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

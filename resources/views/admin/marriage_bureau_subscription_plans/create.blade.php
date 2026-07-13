@extends('admin.master_layout')
@section('title')
<title>Create MB Subscription Plan - Piyari Family</title>
@endsection
@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Create MB Subscription Plan</h1>
        </div>

        <div class="section-body">
            <a href="{{ route('admin.marriage-bureau-subscriptions.index') }}" class="btn btn-primary mb-4"><i class="fas fa-arrow-left"></i> Back to Plans</a>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.marriage-bureau-subscriptions.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Plan Name</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Price (PKR)</label>
                                <input type="number" class="form-control" name="price" required min="0">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Payment Status</label>
                            <select class="form-control" name="payment_status" required>
                                <option value="free">Free</option>
                                <option value="paid" selected>Paid</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Features</label>
                            <textarea class="form-control" name="features" rows="5" placeholder="Enter one feature per line, e.g.&#10;Create &amp; Manage Users&#10;Advanced Search Filters&#10;Profile Boost&#10;Unlimited Messaging"></textarea>
                            <small class="text-muted">Each line will show as a separate feature on the marriage bureau plan card.</small>
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Save Plan</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

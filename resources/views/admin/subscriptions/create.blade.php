@extends('admin.master_layout')
@section('title')
    <title>Create Subscription - Pairi Family</title>
@endsection
@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Create Subscription</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.subscriptions.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Plan Name</label>
                                <input type="text" name="name" class="form-control" placeholder="e.g. VIP Plan" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Price (PKR)</label>
                                <input type="number" step="0.01" name="price" class="form-control" placeholder="e.g. 2499" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Duration (Days)</label>
                                <input type="number" name="duration_days" class="form-control" value="30" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Type</label>
                                <select name="type" class="form-control">
                                    <option value="VIP">VIP</option>
                                    <option value="VVIP">VVIP</option>
                                    <option value="Free">Free</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-primary">Create Subscription</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

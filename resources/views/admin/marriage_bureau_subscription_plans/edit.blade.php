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
                                <input type="text" class="form-control" value="{{ $marriage_bureau_subscription->name }}" readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Payment Status</label>
                                <input type="text" class="form-control" value="{{ ucfirst($marriage_bureau_subscription->payment_status ?? 'paid') }}" readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Price (PKR) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="price" value="{{ old('price', $marriage_bureau_subscription->price) }}" required min="0" step="0.01">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Duration <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="duration_days" value="{{ old('duration_days', $marriage_bureau_subscription->duration_days ?? 1) }}" required min="1">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Duration Unit <span class="text-danger">*</span></label>
                                <select class="form-control" name="duration_unit" required>
                                    <option value="days" @selected(old('duration_unit', $marriage_bureau_subscription->duration_unit ?? 'days') === 'days')>Days</option>
                                    <option value="months" @selected(old('duration_unit', $marriage_bureau_subscription->duration_unit ?? 'days') === 'months')>Months</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Features (read-only — full app access)</label>
                                <ul class="list-group">
                                    @foreach(($marriage_bureau_subscription->features ?? []) as $feature)
                                        <li class="list-group-item">{{ $feature }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Price & Duration</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

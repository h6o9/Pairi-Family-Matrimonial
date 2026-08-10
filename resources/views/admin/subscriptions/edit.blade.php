@extends('admin.master_layout')
@section('title')
    <title>Edit Subscription - Piyari Family</title>
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
                                <input type="text" class="form-control" value="{{ $subscription->name }}" readonly>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Status</label>
                                <input type="text" class="form-control" value="{{ $subscription->payment_status === 'free' ? 'Free (non paid)' : 'Paid' }}" readonly>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Price (PKR) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $subscription->price) }}" required min="0" @if($subscription->type === 'Free') readonly @endif>
                                @if($subscription->type === 'Free')
                                    <small class="text-muted">Free plan price is always 0.</small>
                                @endif
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Duration <span class="text-danger">*</span></label>
                                <input type="number" name="duration_days" class="form-control" value="{{ old('duration_days', $subscription->duration_days) }}" required min="1">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Duration Unit <span class="text-danger">*</span></label>
                                <select name="duration_unit" class="form-control" required>
                                    <option value="days" @selected(old('duration_unit', $subscription->duration_unit ?? 'days') === 'days')>Days</option>
                                    <option value="months" @selected(old('duration_unit', $subscription->duration_unit ?? 'days') === 'months')>Months</option>
                                </select>
                            </div>
                            <!-- <div class="col-md-12 form-group">
                                <label>Features (read-only)</label>
                                <ul class="list-group">
                                    @forelse($subscription->displayFeatures() as $feature)
                                        <li class="list-group-item">{{ $feature }}</li>
                                    @empty
                                        <li class="list-group-item text-muted">No features</li>
                                    @endforelse
                                </ul>
                            </div> -->
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-primary">Update Price & Duration</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@extends('admin.master_layout')
@section('title')
    <title>System Settings - Piyari Family</title>
@endsection
@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>System Settings</h1>
        </div>
        <div class="section-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card mb-3">
                <div class="card-header">
                    <h4>Invite & Earn Settings</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.store') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Reward Points per Registration</label>
                            <div class="col-sm-6">
                                <input type="number" class="form-control" name="invite_reward_points" value="{{ $settings['invite_reward_points'] ?? 50 }}" required>
                                <small class="text-muted">These points will be awarded to the user whose referral link was used during registration.</small>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-primary">Save Settings</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@extends('admin.master_layout')
@section('title')
    <title>Dashboard - Pairi Family</title>
@endsection
@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Pairi Family Dashboard</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="card card-statistic-2">
                        <div class="card-icon shadow-primary bg-primary">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header"><h4>Total Users</h4></div>
                            <div class="card-body">{{ $stats['total_users'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="card card-statistic-2">
                        <div class="card-icon shadow-success bg-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header"><h4>Verified Users</h4></div>
                            <div class="card-body">{{ $stats['verified_users'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="card card-statistic-2">
                        <div class="card-icon shadow-info bg-info">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header"><h4>Complete Profiles</h4></div>
                            <div class="card-body">{{ $stats['completed_profiles'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="card card-statistic-2">
                        <div class="card-icon shadow-warning bg-warning">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header"><h4>Phone Verified</h4></div>
                            <div class="card-body">{{ $stats['phone_verified'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-primary">
                        <i class="fas fa-user-friends"></i> Manage Users
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

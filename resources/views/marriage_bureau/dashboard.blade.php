@extends('marriage_bureau.master_layout')
@section('title')
<title>Dashboard - Piyari Family</title>
@endsection
@section('admin-content')
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Marriage Bureau Dashboard</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="hero text-white hero-bg-image hero-bg-parallax" style="background-image: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%);">
                        <div class="hero-inner">
                            <h2>Welcome, {{ Auth::guard('marriage_bureau')->user()->name }}!</h2>
                            <p class="lead">Manage your bureau, upgrade your subscription, and help people find their matches in heaven.</p>
                            @php
                                $activeSub = \App\Models\MarriageBureauSubscription::where('marriage_bureau_id', Auth::guard('marriage_bureau')->id())->where('status', 'verified')->first();
                            @endphp
                            @if($activeSub)
                                <div class="mt-4">
                                    <a href="#" class="btn btn-outline-white btn-lg btn-icon icon-left"><i class="fas fa-users"></i> Manage Users</a>
                                </div>
                            @else
                                <div class="mt-4">
                                    <a href="#" class="btn btn-warning btn-lg btn-icon icon-left"><i class="fas fa-crown"></i> Get Premium Plan</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Users</h4>
                            </div>
                            <div class="card-body">
                                0
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-crown"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Subscription</h4>
                            </div>
                            <div class="card-body">
                                {{ $activeSub ? 'Active' : 'None' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

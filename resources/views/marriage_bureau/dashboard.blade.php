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
                                $sub = \App\Models\MarriageBureauSubscription::with('plan')->where('marriage_bureau_id', Auth::guard('marriage_bureau')->id())->latest()->first();
                                $hasAccess = $sub && in_array($sub->status, ['verified', 'free']);
                            @endphp

                            @if($sub && $sub->status == 'paid')
                                <div class="mt-4">
                                    <div class="alert alert-warning">
                                        Your subscription plan ({{ $sub->plan->name ?? '' }}) requires payment verification. 
                                        @if($sub->payment_screenshot)
                                            You have already uploaded the screenshot. Please wait for the admin to verify it.
                                        @else
                                            Please click the button below to upload your payment screenshot.
                                        @endif
                                    </div>
                                    @if(!$sub->payment_screenshot)
                                        <button class="btn btn-warning btn-lg btn-icon icon-left" data-bs-toggle="modal" data-bs-target="#verifyModal"><i class="fas fa-file-upload"></i> Verify Subscription</button>
                                    @endif
                                </div>
                            @elseif($hasAccess)
                                <div class="mt-4">
                                    <a href="{{ route('marriage-bureau.users.index') }}" class="btn btn-outline-white btn-lg btn-icon icon-left"><i class="fas fa-users"></i> Manage Users</a>
                                </div>
                            @else
                                <div class="mt-4">
                                    <a href="{{ route('marriage-bureau.subscription.index') }}" class="btn btn-warning btn-lg btn-icon icon-left"><i class="fas fa-crown"></i> Get Premium Plan</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            @if($hasAccess)
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
                                {{ \App\Models\User::where('marriage_bureau_id', Auth::guard('marriage_bureau')->id())->count() }}
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
                                Active ({{ ucfirst($sub->status) }})
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>
</div>

<!-- Verify Modal -->
<div class="modal fade" id="verifyModal" tabindex="-1" role="dialog" aria-labelledby="verifyModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="verifyModalLabel">Verify Subscription Payment</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('marriage-bureau.subscription.upload-screenshot') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="modal-body">
            <div class="form-group">
                <label>Payment Screenshot</label>
                <input type="file" class="form-control" name="payment_screenshot" required accept="image/*">
                <small class="form-text text-muted">Upload a screenshot of your payment receipt.</small>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Upload & Verify</button>
          </div>
      </form>
    </div>
  </div>
</div>
@endsection

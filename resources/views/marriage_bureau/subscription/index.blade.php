@extends('marriage_bureau.master_layout')

@section('title')
<title>Premium Subscriptions - Piyari Family</title>
@endsection

@push('css')
<style>
    .premium-bg {
        background: linear-gradient(135deg, #7B1113 0%, #3e090a 100%);
        border-radius: 15px;
        color: white;
        padding: 40px 20px;
        position: relative;
        overflow: hidden;
        text-align: center;
        box-shadow: 0 10px 20px rgba(123, 17, 19, 0.3);
    }
    .premium-bg::before {
        content: '\f521';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute;
        font-size: 150px;
        opacity: 0.05;
        top: -20px;
        right: -20px;
    }
    .crown-icon {
        font-size: 50px;
        color: #F5A623;
        margin-bottom: 15px;
    }
    .plan-card {
        background-color: #FFF5F5;
        border: 2px solid transparent;
        border-radius: 15px;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .plan-card:hover, .plan-card.selected {
        border-color: #F5A623;
        box-shadow: 0 5px 15px rgba(245, 166, 35, 0.2);
    }
    .plan-card.selected {
        background-color: #fffaf0;
    }
    .feature-list {
        list-style: none;
        padding: 0;
        text-align: left;
    }
    .feature-list li {
        margin-bottom: 10px;
        color: #555;
    }
    .feature-list li i {
        color: #F5A623;
        margin-right: 10px;
    }
    .payment-method-card {
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .payment-method-card:hover, .payment-method-card.selected {
        border-color: #7B1113;
        background-color: #fff9f9;
    }
    .payment-icon {
        font-size: 24px;
        margin-right: 15px;
        color: #7B1113;
    }
    .btn-premium {
        background: linear-gradient(to right, #7B1113, #a21719);
        color: white;
        border: none;
        border-radius: 25px;
        padding: 10px 30px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .btn-premium:hover {
        background: linear-gradient(to right, #5a0c0e, #7B1113);
        color: white;
    }
</style>
@endsection

@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($activeSub)
            <div class="row justify-content-center mb-5">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="premium-bg text-center">
                        <i class="fas fa-crown crown-icon text-white"></i>
                        <h2 class="font-weight-bold">You're Premium Now!</h2>
                        <p class="mt-3">Welcome to Piyari Family Premium. Your journey to helping people find the perfect match just got better!</p>
                        
                        <div class="card mt-4 text-dark" style="border-radius: 15px;">
                            <div class="card-body">
                                <h5><i class="fas fa-check-circle text-success"></i> Subscription Active</h5>
                                <p class="mb-0">Your Marriage Bureau Premium plan is fully active.</p>
                                <a href="#" class="btn btn-primary mt-3"><i class="fas fa-users"></i> Start Exploring Matches</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-8">
                    <div class="premium-bg mb-5">
                        <i class="fas fa-crown crown-icon"></i>
                        <h2 class="font-weight-bold">This Feature is Premium</h2>
                        <p>Upgrade to unlock unlimited access and start managing users</p>
                    </div>

                    <form action="{{ route('marriage-bureau.subscription.store') }}" method="POST" id="subscriptionForm">
                        @csrf
                        <div class="row mb-4">
                            @foreach($plans as $plan)
                            <div class="col-md-6 mb-3">
                                <div class="card plan-card h-100 p-3" onclick="selectPlan({{ $plan->id }}, {{ $plan->price }})" id="plan-{{ $plan->id }}">
                                    <div class="card-body text-center">
                                        <h4 class="font-weight-bold" style="color: #7B1113;">{{ $plan->name }}</h4>
                                        <h2 class="my-3" style="color: #F5A623;">PKR {{ number_format($plan->price, 0) }}</h2>
                                        <p class="text-muted">{{ $plan->description ?? 'Premium features unlocked.' }}</p>
                                        <input type="radio" name="plan_id" value="{{ $plan->id }}" class="d-none" id="radio-plan-{{ $plan->id }}" required>
                                        
                                        <hr>
                                        <ul class="feature-list">
                                            <li><i class="fas fa-unlock"></i> Create & Manage Users</li>
                                            <li><i class="fas fa-search"></i> Advanced Search Filters</li>
                                            <li><i class="fas fa-bolt"></i> Profile Boost</li>
                                            <li><i class="fas fa-comments"></i> Unlimited Messaging</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="card shadow-sm mb-4 d-none" id="paymentSection">
                            <div class="card-header border-bottom">
                                <h4>Complete Payment</h4>
                            </div>
                            <div class="card-body">
                                <h6 class="mb-3">Choose Payment Method</h6>
                                
                                <label class="payment-method-card d-flex align-items-center w-100">
                                    <input type="radio" name="payment_method" value="easypaisa" class="mr-3" required>
                                    <i class="fas fa-mobile-alt payment-icon"></i>
                                    <div>
                                        <h6 class="mb-0">Easypaisa / JazzCash</h6>
                                        <small class="text-muted">Pay directly via mobile account</small>
                                    </div>
                                </label>

                                <label class="payment-method-card d-flex align-items-center w-100">
                                    <input type="radio" name="payment_method" value="bank" class="mr-3">
                                    <i class="fas fa-university payment-icon"></i>
                                    <div>
                                        <h6 class="mb-0">Bank Transfer</h6>
                                        <small class="text-muted">Direct transfer to our account</small>
                                    </div>
                                </label>
                                
                                <div class="text-center mt-4">
                                    <p class="text-muted small"><i class="fas fa-shield-alt text-success"></i> Your payment is 256-bit SSL encrypted and 100% secure.</p>
                                    <button type="submit" class="btn btn-premium btn-lg w-100" id="payButton">
                                        <i class="fas fa-lock"></i> Pay PKR <span id="displayPrice">0</span> Securely
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </section>
</div>
@endsection

@push('js')
<script>
    function selectPlan(id, price) {
        $('.plan-card').removeClass('selected');
        $('#plan-' + id).addClass('selected');
        $('#radio-plan-' + id).prop('checked', true);
        
        $('#displayPrice').text(new Intl.NumberFormat().format(price));
        $('#paymentSection').removeClass('d-none');
        
        // Scroll to payment
        $('html, body').animate({
            scrollTop: $("#paymentSection").offset().top - 100
        }, 500);
    }
</script>
@endpush

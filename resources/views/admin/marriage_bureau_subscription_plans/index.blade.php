@extends('admin.master_layout')

@section('title')
<title>MB Subscription Plans - Piyari Family</title>
@stop

@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Marriage Bureau Subscription Plans</h1>
        </div>

        <div class="section-body">
            {{-- Create disabled — only one fixed MB plan
            @if($plans->isEmpty())
            <a href="{{ route('admin.marriage-bureau-subscriptions.create') }}" class="btn btn-primary mb-4"><i class="fas fa-plus"></i> Create New Plan</a>
            @endif
            --}}
            <p class="text-muted mb-3">Only one MB subscription plan. Edit <strong>Price</strong> and <strong>Duration</strong> only. Features stay full app access (read-only).</p>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped data-table" id="plansTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Duration</th>
                                    <th>Payment</th>
                                    <th>Features</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($plans as $plan)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $plan->name }}</td>
                                    <td>PKR {{ number_format($plan->price) }}</td>
                                    <td>{{ $plan->durationLabel() }}</td>
                                    <td>
                                        <span class="badge badge-{{ $plan->payment_status === 'free' ? 'secondary' : 'success' }}">
                                            {{ ucfirst($plan->payment_status ?? 'paid') }}
                                        </span>
                                    </td>
                                    <td>
                                        <ul class="mb-0 pl-3">
                                            @foreach(($plan->features ?? []) as $feature)
                                                <li>{{ $feature }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.marriage-bureau-subscriptions.edit', $plan->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                        {{-- Delete disabled --}}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td></td>
                                    <td>No MB plan found. Run SubscriptionPlansSeeder.</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@stop

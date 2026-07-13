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
            @if($plans->isEmpty())
            <a href="{{ route('admin.marriage-bureau-subscriptions.create') }}" class="btn btn-primary mb-4"><i class="fas fa-plus"></i> Create New Plan</a>
            @else
            <p class="text-muted">Only one subscription plan is supported. Edit it below to change what marriage bureaus see.</p>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped data-table" id="plansTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($plans as $plan)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $plan->name }}</td>
                                    <td>PKR {{ number_format($plan->price) }}</td>
                                    <td>
                                        @if($plan->status == 'active')
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.marriage-bureau-subscriptions.edit', $plan->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                        <x-admin.delete-button class="deleteForm" data-url="{{ route('admin.marriage-bureau-subscriptions.destroy', $plan->id) }}" title="Delete" />
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<x-admin.delete-modal />
@stop
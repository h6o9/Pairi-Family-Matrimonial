@extends('admin.master_layout')
@section('title')
    <title>FAQs - Piyari Family</title>
@endsection
@push('css')
<style>
    .faq-answer-preview {
        display: -webkit-box;
        max-width: 520px;
        overflow: hidden;
        color: #34395e;
        line-height: 1.55;
        text-decoration: none;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 3;
    }
    .faq-answer-preview:hover { color: #7B1E3A; text-decoration: none; }
    .faq-answer-content { line-height: 1.7; overflow-wrap: anywhere; }
</style>
@endpush
@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header d-flex justify-content-between align-items-center">
            <h1>FAQs</h1>
            <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add FAQ</a>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Question</th>
                                    <th>Answer</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($faqs as $faq)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $faq->question }}</td>
                                        <td>
                                            <a href="javascript:;" class="faq-answer-preview" data-bs-toggle="modal" data-bs-target="#faqAnswerModal{{ $faq->id }}" title="View complete answer">
                                                {{ trim(preg_replace('/\s+/', ' ', strip_tags($faq->answer))) }}
                                            </a>
                                        </td>
                                        <td><span class="badge badge-{{ $faq->status === 'active' ? 'success' : 'danger' }}">{{ ucfirst($faq->status) }}</span></td>
                                        <td>
                                            <div class="table-actions">
                                                <a href="{{ route('admin.faqs.edit', $faq) }}" class="btn btn-info btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                                                <x-admin.delete-button class="deleteForm" data-url="{{ route('admin.faqs.destroy', $faq) }}" title="Delete" />
                                            </div>
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
@foreach($faqs as $faq)
    <div class="modal fade" id="faqAnswerModal{{ $faq->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $faq->question }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body faq-answer-content">
                    {!! $faq->answer !!}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endforeach
<x-admin.delete-modal />
@endsection

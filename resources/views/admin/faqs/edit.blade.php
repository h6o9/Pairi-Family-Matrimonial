@extends('admin.master_layout')
@section('title')
    <title>Edit FAQ - Piyari Family</title>
@endsection
@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header"><h1>Edit FAQ</h1></div>
        <div class="section-body">
            <div class="card"><div class="card-body">
                <form action="{{ route('admin.faqs.update', $faq) }}" method="POST">
                    @include('admin.faqs._form')
                </form>
            </div></div>
        </div>
    </section>
</div>
@endsection

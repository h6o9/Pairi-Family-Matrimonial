@extends('admin.master_layout')
@section('title')
    <title>{{ $page->title }} - Piyari Family</title>
@endsection
@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header"><h1>{{ $page->title }}</h1></div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.content.update', ['type' => $type]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label>Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $page->title) }}" required maxlength="255">
                        </div>
                        <div class="form-group">
                            <label>Content <span class="text-danger">*</span></label>
                            <textarea name="content" class="form-control rich-editor" rows="16">{{ old('content', $page->content) }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" required>
                                <option value="active" @selected(old('status', $page->status) === 'active')>Active</option>
                                <option value="inactive" @selected(old('status', $page->status) === 'inactive')>Inactive</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Content</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('js')
<script>
    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: '.rich-editor',
            height: 450,
            menubar: false,
            plugins: 'lists link code',
            toolbar: 'undo redo | blocks | bold italic | bullist numlist | link | code'
        });
    }
</script>
@endpush

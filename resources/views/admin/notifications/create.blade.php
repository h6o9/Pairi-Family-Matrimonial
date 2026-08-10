@extends('admin.master_layout')
@section('title')
    <title>Send Notification - Piyari Family</title>
@endsection
@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ route('admin.notifications.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Send Notification</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.notifications.store') }}" method="POST" id="notificationForm">
                        @csrf
                        <div class="form-group">
                            <label>Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required maxlength="255">
                            @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label>Message <span class="text-danger">*</span></label>
                            <textarea name="message" class="form-control" rows="12" cols="80" required maxlength="5000" style="min-height: 220px; resize: vertical;">{{ old('message') }}</textarea>
                            @error('message') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label>Send To <span class="text-danger">*</span></label>
                            <div class="mt-2">
                                <label class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="send_to" value="all" class="custom-control-input send-to-radio" {{ old('send_to', 'all') === 'all' ? 'checked' : '' }}>
                                    <span class="custom-control-label">All Users</span>
                                </label>
                                <label class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="send_to" value="selected" class="custom-control-input send-to-radio" {{ old('send_to') === 'selected' ? 'checked' : '' }}>
                                    <span class="custom-control-label">Selected Users</span>
                                </label>
                            </div>
                            @error('send_to') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group" id="user-select-wrap" style="{{ old('send_to') === 'selected' ? '' : 'display:none;' }}">
                            <label>Search Users by Email <span class="text-danger">*</span></label>
                            <select name="user_ids[]" id="user_ids" class="form-control" multiple>
                                @if(old('user_ids'))
                                    @foreach(\App\Models\User::whereIn('id', old('user_ids'))->get(['id','name','email']) as $user)
                                        <option value="{{ $user->id }}" selected>{{ $user->email }} ({{ $user->name }})</option>
                                    @endforeach
                                @endif
                            </select>
                            <small class="text-muted">Type email or name to search and select users (e.g. Ali, Hammond, Ahmed).</small>
                            @error('user_ids') <span class="text-danger d-block">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Send Notification
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('js')
<script>
    $(function () {
        function toggleUserSelect() {
            var selected = $('input[name="send_to"]:checked').val() === 'selected';
            $('#user-select-wrap').toggle(selected);
            if (!selected) {
                $('#user_ids').val(null).trigger('change');
            }
        }

        $('.send-to-radio').on('change', toggleUserSelect);
        toggleUserSelect();

        $('#user_ids').select2({
            placeholder: 'Search by email or name...',
            width: '100%',
            allowClear: true,
            minimumInputLength: 2,
            ajax: {
                url: @json(route('admin.notifications.users-search')),
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return { q: params.term };
                },
                processResults: function (data) {
                    return { results: data.results || [] };
                },
                cache: true
            }
        });
    });
</script>
@endpush

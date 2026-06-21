@extends('admin.master_layout')
@section('title')
    <title>{{ $user->name }} - Pairi Family</title>
@endsection
@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>User Profile</h1>
            <div class="section-header-breadcrumb">
                <a href="{{ route('admin.users.index') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Back</a>
            </div>
        </div>
        <div class="section-body">
                    <div class="card">
                        <div class="card-body">
                    <div class="row mb-4 align-items-center">
                        <div class="col-md-2 text-center">
                            @if($user->profile_photo)
                                <img src="{{ $user->profile_photo }}" class="rounded-circle border" style="width:110px;height:110px;object-fit:cover;" alt="Profile">
                            @else
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width:110px;height:110px;">
                                    <i class="fas fa-user fa-3x text-muted"></i>
                                </div>
                                        @endif
                        </div>
                        <div class="col-md-7">
                            <h3 class="mb-1">{{ $user->name }} @if($user->age), {{ $user->age }}@endif</h3>
                            <p class="text-muted mb-2">
                                {{ trim(implode(', ', array_filter([$user->city, $user->country]))) ?: 'Location not set' }}
                                @if($user->job_title) &bull; {{ $user->job_title }} @endif
                            </p>
                            <div class="mb-2">
                                        @if($user->is_verified)
                                    <span class="badge badge-success"><i class="fas fa-check"></i> Email Verified</span>
                                        @else
                                    <span class="badge badge-warning">Email Pending</span>
                                    <button type="button" class="btn btn-sm btn-success ml-1 verify-email-btn" data-url="{{ route('admin.users.verify-email', $user->id) }}">
                                        Verify Email
                                                    </button>
                                                @endif

                                @if($user->phone_verified)
                                    <span class="badge badge-info"><i class="fas fa-phone"></i> Phone Verified</span>
                                            @else
                                    <span class="badge badge-secondary">Phone Pending</span>
                                    @if($user->phone)
                                    <button type="button" class="btn btn-sm btn-info ml-1 verify-phone-btn" data-url="{{ route('admin.users.verify-phone', $user->id) }}">
                                        Verify Phone
                                    </button>
                                            @endif
                                            @endif

                                @if($user->profile_completed)
                                    <span class="badge badge-primary">Profile Complete</span>
                                                @endif
                                            </div>
                            <small class="text-muted">Registered: {{ $user->created_at?->format('d M Y, h:i A') }}</small>
                                        </div>
                        <div class="col-md-3 text-right">
                            <button type="button" class="btn btn-{{ $user->status === 'active' ? 'warning' : 'success' }} toggle-status-btn"
                                data-url="{{ route('admin.users.toggle-status', $user->id) }}">
                                {{ $user->status === 'active' ? 'Deactivate Account' : 'Activate Account' }}
                            </button>
                                        </div>
                                    </div>

                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#personal">Personal</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#education">Education & Career</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#faith">Faith & Physical</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#photos">Photos</a></li>
                    </ul>

                    <div class="tab-content pt-3">
                        <div class="tab-pane fade show active" id="personal">
                            <table class="table table-bordered">
                                <tr><th width="20%">Full Name</th><td>{{ $user->name }}</td><th width="20%">Gender</th><td>{{ ucfirst($user->gender ?? '-') }}</td></tr>
                                <tr><th>Date of Birth</th><td>{{ $user->birthday?->format('d M Y') ?? '-' }}</td><th>Age</th><td>{{ $user->age ?? '-' }}</td></tr>
                                <tr><th>Email</th><td>{{ $user->email }}</td><th>Phone</th><td>{{ $user->phone ?? '-' }}</td></tr>
                                <tr><th>Country</th><td>{{ $user->country ?? '-' }}</td><th>City</th><td>{{ $user->city ?? '-' }}</td></tr>
                                <tr><th>Marital Status</th><td>{{ $user->marital_status ?? '-' }}</td><th>Height</th><td>{{ $user->height ?? '-' }}</td></tr>
                                <tr><th>Residential Status</th><td>{{ $user->residential_status ?? '-' }}</td><th>Social Login</th><td>{{ ucfirst($user->social_provider ?? 'Email') }}</td></tr>
                                <tr><th>About Me</th><td colspan="3">{{ $user->bio ?? '-' }}</td></tr>
                                @if(!empty($user->interests))
                                <tr><th>Interests</th><td colspan="3">
                                    @foreach($user->interests as $interest)
                                        <span class="badge badge-primary">{{ $interest }}</span>
                                                                @endforeach
                                </td></tr>
                                @endif
                            </table>
                                                            </div>

                        <div class="tab-pane fade" id="education">
                            <table class="table table-bordered">
                                <tr><th width="20%">Qualification</th><td>{{ $user->qualification ?? '-' }}</td><th width="20%">Field of Study</th><td>{{ $user->field_of_study ?? '-' }}</td></tr>
                                <tr><th>University</th><td>{{ $user->university ?? '-' }}</td><th>Graduation Year</th><td>{{ $user->graduation_year ?? '-' }}</td></tr>
                                <tr><th>Employment Type</th><td>{{ ucfirst(str_replace('_', ' ', $user->employment_type ?? '-')) }}</td><th>Job Title</th><td>{{ $user->job_title ?? '-' }}</td></tr>
                                <tr><th>Company</th><td>{{ $user->company ?? '-' }}</td><th>Monthly Income</th><td>{{ $user->monthly_income ?? '-' }}</td></tr>
                            </table>
                                                        </div>

                        <div class="tab-pane fade" id="faith">
                            <table class="table table-bordered">
                                <tr><th width="20%">Religion</th><td>{{ $user->religion ?? '-' }}</td><th width="20%">Community</th><td>{{ $user->community ?? '-' }}</td></tr>
                                <tr><th>Sect</th><td>{{ $user->sect ?? '-' }}</td><th>Mother Tongue</th><td>{{ $user->mother_tongue ?? '-' }}</td></tr>
                                <tr><th>Other Languages</th><td colspan="3">
                                    @forelse($user->other_languages ?? [] as $lang)
                                        <span class="badge badge-secondary">{{ $lang }}</span>
                                    @empty - @endforelse
                                </td></tr>
                                <tr><th>Weight</th><td>{{ $user->weight ?? '-' }}</td><th>Body Type</th><td>{{ ucfirst($user->body_type ?? '-') }}</td></tr>
                                <tr><th>Complexion</th><td>{{ ucfirst($user->complexion ?? '-') }}</td><th>Physical Disability</th><td>{{ $user->physical_disability ? 'Yes' : 'No' }}</td></tr>
                            </table>
                        </div>

                        <div class="tab-pane fade" id="photos">
                            @if(!empty($user->photos))
                            <div class="row">
                                @foreach($user->photos as $photo)
                                <div class="col-md-3 mb-3 text-center">
                                    <img src="{{ asset('storage/' . ($photo['path'] ?? '')) }}" class="img-fluid rounded border mb-1" alt="Photo">
                                    @if($photo['is_main'] ?? false)
                                        <span class="badge badge-success">Main Photo</span>
                                                    @endif
                                </div>
                                @endforeach
                            </div>
                            @else
                                <p class="text-muted">No photos uploaded yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function postAction(url, $btn, successMsg) {
    const original = $btn.html();
    $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

    $.post(url, { _token: '{{ csrf_token() }}' }, function(res) {
        if (res.success) {
            Swal.fire('Success', successMsg || res.message, 'success').then(() => location.reload());
        } else {
            Swal.fire('Error', res.message, 'error');
            $btn.prop('disabled', false).html(original);
        }
    }).fail(function() {
        Swal.fire('Error', 'Something went wrong.', 'error');
        $btn.prop('disabled', false).html(original);
    });
}

$(document).on('click', '.verify-email-btn', function() {
    postAction($(this).data('url'), $(this));
});

$(document).on('click', '.verify-phone-btn', function() {
    postAction($(this).data('url'), $(this));
});

$(document).on('click', '.toggle-status-btn', function() {
    postAction($(this).data('url'), $(this));
});
</script>
@endpush

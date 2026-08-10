<link rel="stylesheet" href="{{ asset('backend/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/fontawesome/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/css/style.css') }}?v={{$setting?->version}}">
<link rel="stylesheet" href="{{ asset('backend/css/bootstrap-social.css') }}">
<link rel="stylesheet" href="{{ asset('backend/css/components.css') }}">
<link rel="stylesheet" href="{{ asset('global/toastr/toastr.min.css') }}">
<link rel="stylesheet" href="{{ asset('global/css/password-toggle.css') }}?v=2">
<style>
input[type="password"]::-ms-reveal,
input[type="password"]::-ms-clear { display: none !important; }
.password-input-wrap { position: relative !important; display: block !important; width: 100% !important; }
.password-input-wrap > input { padding-right: 2.75rem !important; width: 100% !important; }
.password-input-wrap > .password-toggle-btn {
    position: absolute !important; right: 0.75rem !important; top: 50% !important; left: auto !important;
    transform: translateY(-50%) !important; border: 0 !important; background: transparent !important;
    color: #6c757d !important; padding: 0 !important; margin: 0 !important; cursor: pointer !important;
    z-index: 5 !important; width: 1.75rem !important; height: 1.75rem !important;
    display: inline-flex !important; align-items: center !important; justify-content: center !important;
    appearance: none !important; -webkit-appearance: none !important; box-shadow: none !important;
}
</style>
<link rel="stylesheet" href="{{ asset('backend/css/bootstrap-toggle.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/css/dev.css') }}?v={{$setting?->version}}">
@if (session()->has('text_direction') && session()->get('text_direction') !== 'ltr')
    <link rel="stylesheet" href="{{ asset('backend/css/rtl.css') }}?v={{$setting?->version}}">
    <link rel="stylesheet" href="{{ asset('backend/css/dev_rtl.css') }}?v={{$setting?->version}}">
@endif
<link rel="stylesheet" href="{{ asset('backend/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/css/tagify.css') }}">
<link rel="stylesheet" href="{{ asset('backend/css/bootstrap-tagsinput.css') }}">
<link rel="stylesheet" href="{{ asset('backend/css/fontawesome-iconpicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/css/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/clockpicker/dist/bootstrap-clockpicker.css') }}">
<link rel="stylesheet" href="{{ asset('backend/datetimepicker/jquery.datetimepicker.css') }}">
<link rel="stylesheet" href="{{ asset('backend/css/iziToast.min.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.11/css/dataTables.bootstrap5.min.css">

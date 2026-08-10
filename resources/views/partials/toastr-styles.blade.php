<link rel="stylesheet" href="{{ asset('global/toastr/toastr.min.css') }}">
<link rel="stylesheet" href="{{ asset('global/css/password-toggle.css') }}?v=2">
{{-- Inline fallback so eye icon never falls outside the input if CSS file is cached/missing --}}
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
.password-input-wrap > .password-toggle-btn i { pointer-events: none !important; font-size: 1rem !important; }
</style>

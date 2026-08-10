<script src="{{ asset('global/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('global/js/password-toggle.js') }}?v=2"></script>
<script>
    // #region agent log
    fetch('http://127.0.0.1:7559/ingest/b21f2b75-4e25-47af-b7e2-c54d21d8b8b5',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'64ce68'},body:JSON.stringify({sessionId:'64ce68',runId:'post-fix',hypothesisId:'MB1',location:'toastr-scripts.blade.php',message:'MB auth toast runtime check',data:{jqueryLoaded:typeof window.jQuery!=='undefined',toastrLoaded:typeof toastr!=='undefined',hasMessage:@json(session()->has('message')),alertType:@json(Session::get('alert-type')),messageText:@json(Session::get('message')),path:window.location.pathname},timestamp:Date.now()})}).catch(function(){});
    // #endregion
    @session('message')
    var type = "{{ Session::get('alert-type', 'info') }}"
    switch (type) {
        case 'info':
            toastr.info("{{ $value }}");
            break;
        case 'success':
            toastr.success("{{ $value }}");
            break;
        case 'warning':
            toastr.warning("{{ $value }}");
            break;
        case 'error':
            toastr.error("{{ $value }}");
            break;
    }
    @endsession
</script>

@if ($errors->any())
    @foreach ($errors->all() as $error)
        <script>
            toastr.error('{{ $error }}');
        </script>
    @endforeach
@endif

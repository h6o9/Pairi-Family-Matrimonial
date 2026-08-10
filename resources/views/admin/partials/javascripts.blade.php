<script src="{{ asset('global/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('backend/js/popper.min.js') }}"></script>
<script src="{{ asset('backend/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('backend/js/jquery.nicescroll.min.js') }}"></script>
<script src="{{ asset('backend/js/moment.min.js') }}"></script>
<script src="{{ asset('backend/js/stisla.js') }}?v={{ $setting?->version }}"></script>
<script src="{{ asset('backend/js/scripts.js') }}?v={{ $setting?->version }}"></script>
<script src="{{ asset('backend/js/select2.min.js') }}"></script>
<script src="{{ asset('backend/js/tagify.js') }}"></script>
<script src="{{ asset('global/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('backend/js/bootstrap-toggle.jquery.min.js') }}"></script>
<script src="{{ asset('backend/js/fontawesome-iconpicker.min.js') }}"></script>
<script src="{{ asset('backend/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('backend/clockpicker/dist/bootstrap-clockpicker.js') }}"></script>
<script src="{{ asset('backend/datetimepicker/jquery.datetimepicker.js') }}"></script>
<script src="{{ asset('backend/js/iziToast.min.js') }}"></script>
<script src="{{ asset('backend/js/modules-toastr.js') }}"></script>
<script src="{{ asset('backend/tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('backend/js/jquery.uploadPreview.min.js') }}"></script>
<script src="{{ asset('website/js/Font-Awesome.js') }}"></script>
<script src="{{ asset('backend/js/custom.js') }}?v={{ $setting?->version }}"></script>
<script src="{{ asset('global/js/password-toggle.js') }}?v=2"></script>
<script src="https://cdn.datatables.net/1.13.11/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.11/js/dataTables.bootstrap5.min.js"></script>

<script>
    // #region agent log
    fetch('http://127.0.0.1:7559/ingest/b21f2b75-4e25-47af-b7e2-c54d21d8b8b5',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'64ce68'},body:JSON.stringify({sessionId:'64ce68',runId:'post-fix',hypothesisId:'C',location:'javascripts.blade.php:toastr-check',message:'Toastr + flash state on page load',data:{toastrLoaded:typeof toastr!=='undefined',hasMessage:@json(session()->has('message')),hasSuccess:@json(session()->has('success')),alertType:@json(Session::get('alert-type')),messageText:@json(Session::get('message')),successText:@json(Session::get('success')),passwordInputs:document.querySelectorAll('input[type=password]').length,passwordToggles:document.querySelectorAll('.password-toggle-btn').length},timestamp:Date.now()})}).catch(()=>{});
    // #endregion
    @session('message')
    var type = "{{ Session::get('alert-type', 'info') }}"
    // #region agent log
    fetch('http://127.0.0.1:7559/ingest/b21f2b75-4e25-47af-b7e2-c54d21d8b8b5',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'64ce68'},body:JSON.stringify({sessionId:'64ce68',runId:'post-fix',hypothesisId:'D',location:'javascripts.blade.php:toastr-fire',message:'Toastr branch will fire',data:{type:type,value:@json($value)},timestamp:Date.now()})}).catch(()=>{});
    // #endregion
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

<script>
    $(document).ready(function() {
        const sidebarSubmenuSelector = '.sidebar-submenu, .mb-sidebar-submenu';
        const sidebarGroupSelector = '.sidebar-group-toggle, .mb-sidebar-toggle';

        function closeSidebarSections() {
            document.querySelectorAll(sidebarSubmenuSelector).forEach(function(menu) {
                if (typeof bootstrap !== 'undefined' && bootstrap.Collapse) {
                    const instance = bootstrap.Collapse.getInstance(menu);
                    if (instance) {
                        instance.dispose();
                    }
                }

                menu.classList.remove('show', 'collapsing');
                menu.classList.add('collapse');
                menu.style.removeProperty('height');
            });

            document.querySelectorAll(sidebarGroupSelector).forEach(function(toggle) {
                toggle.setAttribute('aria-expanded', 'false');
            });
        }

        document.querySelectorAll('[data-toggle="sidebar"]').forEach(function(sidebarToggle) {
            sidebarToggle.addEventListener('click', closeSidebarSections, true);
        });

        const sidebarStateObserver = new MutationObserver(function() {
            if (document.body.classList.contains('sidebar-mini') || document.body.classList.contains('sidebar-gone')) {
                closeSidebarSections();
            }
        });
        sidebarStateObserver.observe(document.body, { attributes: true, attributeFilter: ['class'] });

        if (document.body.classList.contains('sidebar-mini') || document.body.classList.contains('sidebar-gone')) {
            closeSidebarSections();
        }

        $(document).on('click', '.deleteForm', function() {
            const url = $(this).data('url');
            $('#deleteForm').attr('action', url);
        })

        $("[name='name'], [name='title']").on('input', function() {
            $("[name='slug']").val(convertToSlug($(this).val()));
        })

        if ($.fn.DataTable) {
            $('.data-table').each(function() {
                var $table = $(this);
                var lastCol = $table.find('thead th').length - 1;
                $table.DataTable({
                    pageLength: 10,
                    lengthMenu: [10, 25, 50, 100],
                    order: [],
                    columnDefs: [
                        { orderable: false, searchable: false, targets: lastCol }
                    ],
                    language: {
                        search: '',
                        searchPlaceholder: 'Search...',
                        emptyTable: 'No records found.'
                    }
                });
            });
        }
    })

    function convertToSlug(text = '') {
        return text.toString().toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');
    }

    function prevImage(inputId, previewId, labelId) {
        $.uploadPreview({
            input_field: "#" + inputId,
            preview_box: "#" + previewId,
            label_field: "#" + labelId,
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Change Image') }}",
            no_label: false,
            success_callback: null
        });
    }

    function handleStatus(route) {
        $.ajax({
            url: route,
            type: 'post',
            headers: {
                'Accept': 'application/json'
            },
            success: function(res) {
                toastr.success(res.message);
            },
            error: function(err) {
                handleFetchError(err)
            }
        })
    }

    function handleFetchError(err) {
        if (err.status == 500) {
            toastr.error('Something went wrong!')
        } else {
            toastr.error(err.responseJSON.message)
        }
    }
</script>

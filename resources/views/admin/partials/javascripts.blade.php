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

<script>
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

<script>
    $(document).ready(function() {
        $('.deleteForm').on('click', function() {
            const url = $(this).data('url');
            $('#deleteForm').attr('action', url);

            $('#deleteModal').modal('show');
        })

        $("[name='name'], [name='title']").on('input', function() {
            $("[name='slug']").val(convertToSlug($(this).val()));
        })
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

<script !src="">
    toastr.options = { 
        "progressBar": false,
        "showDuration": "2000",
        "hideDuration": "2000",
        "timeOut": "2000",
        "extendedTimeOut": "0",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    @if(Session::has('message'))
    var type = "{{ Session::get('alert-type', 'info') }}";
    switch (type) {
        case 'info':
        toastr.info("{{ Session::get('message') }}");
        break;

        case 'warning':
        toastr.warning("{{ Session::get('message') }}");
        break;

        case 'success':
        toastr.success("{{ Session::get('message') }}");
        break;

        case 'error':
        toastr.error("{{ Session::get('message') }}");
        break;
    }
    @elseif(count($errors) > 0)
    @foreach($errors->all() as $error)
    toastr.error("{{ $error }}");
    @endforeach
    @endif
    
    
</script>

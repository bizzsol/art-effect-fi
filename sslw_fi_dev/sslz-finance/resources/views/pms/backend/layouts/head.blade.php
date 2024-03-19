<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <!-- Favicon -->
    <link rel="icon" href="{{ asset(session()->get('system-information')['icon']) }}" type="image/png">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" media='screen,print'>
    <link rel="stylesheet" href="{{ asset('assets/css/all.css') }}" media='screen,print'>
    @stack('css')
    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css?v=1.3') }}" media='screen,print'>
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}" media='screen,print'>
     <!-- jQuery Confirm -->
    <link rel="stylesheet" href="{{asset('assets/js/jquery-confirm/jquery-confirm.min.css')}}" />
     <!-- toastr alert -->
    <link rel="stylesheet" href="{{asset('notification_assets/css/toastr.min.css')}}" />
    <link rel="stylesheet" href="{{asset('plugins/air-datepicker/css/datepicker.min.css')}}" />
    <link rel="stylesheet" href="{{asset('plugins/summernote/summernote.min.css')}}" />
    {{-- <link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"> --}}

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.21/b-1.6.3/b-flash-1.6.3/b-html5-1.6.3/b-print-1.6.3/fc-3.3.1/fh-3.1.7/r-2.2.5/sc-2.0.2/datatables.min.css"/>

    @yield('page-css')

    <style type="text/css" media="screen">
        .dataTables_wrapper .dt-buttons {
            padding-left: 0% !important;
        }

        .dataTables_scroll{
            margin-bottom: 10px;
        }

        .pagination{
            margin-bottom: 0px !important;
        }

        .select2-container{
            /*width:  100% !important;*/
            display: block;
        }
        .select2-container--default .select2-results__option[aria-disabled=true]{
            color: black !important;
            font-weight: bold !important;
        }

        #contextMenu .item {
            cursor: pointer;
            transition: 1s;
        }
        #contextMenu .item:hover{
            background: #fff5f4;
            transition: 1s;
        }
        .mask-money{
            text-align:right;
        }
        .rating-container .filled-stars{
           margin-top: 30px !important;
        }
        .rating-container .rating-stars{
            margin-top:-15px !important;
        }
    </style>
</head>
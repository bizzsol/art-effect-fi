<!DOCTYPE html>
<html lang="en">
@include('accounting.backend.layouts.head')
<body>
    <!------------------------------------------------------------------------------------------------>
    @include('accounting.backend.layouts.pre-loader')
    <!-- WRAPPER ------------------------------------------------------------------------------------->
    <div id="app">
        <!-- Wrapper Start -->
        <div class="wrapper">
            <!------------------------------------------------------------------------------------------------>
            @include('accounting.backend.menus.left-menu')
            <!------------------------------------------------------------------------------------------------>
            <!-- Page Content  -->
            <div id="content-page" class="content-page">
                @include('accounting.backend.menus.header-menu')
                <!------------------------------------------------------------------------------------------------>
                <main class="">
                  <div id="main-body" class="container-fluid">
                    @yield('main-content')
                </div>
            </main>
            <!------------------------------------------------------------------------------------------------>
            @include('accounting.backend.layouts.footer')
        </div>
        <div class="app-loader">
            <i class="fa fa-spinner fa-spin"></i>
        </div>
    </div>
    <!-- END WRAPPER --------------------------------------------------------------------------------->
</div>
<!------------------------------------------------------------------------------------------------>
@include('accounting.backend.layouts.script')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
@yield('page-script')
@include('accounting.backend.layouts.toster-script')
@include('contextmenu')
</body>

</html>

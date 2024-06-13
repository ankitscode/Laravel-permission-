<!doctype html >
    <html lang="{{Session::get('locale')}}" data-layout="vertical" data-layout-style="default" data-layout-position="fixed" data-topbar="light" data-sidebar="light" data-sidebar-size="lg" data-layout-width="fluid" data-sidebar-image="none" dir="{{Session::get('locale')=="ar"?"rtl":"ltr"}}">
<head>
    <title>{{ env('APP_NAME', 'Admin Panel') }} - @yield('title')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Admin & Dashboard" name="description" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <link href="//cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css"/> --}}
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('assets/images/favicon.ico')}}">
    @include('layout.header')

</head>

@section('body')
    @include('layout.body')
@show
    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('layout.navbar')
        @include('layout.sidebar')
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- container-fluid -->
            </div>
            {{-- @include('admin.component.modal.coupon.coupon_edit_modal') --}}
            <!-- End Page-content -->
            @include('layout.footer')

        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

    {{-- @include('layouts.admin.footer.customizer') --}}

    <!-- JAVASCRIPT -->
    @include('layout.footerJS')
</body>

</html>

@yield('css')

{{-- <link href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css"/>  <!--datatabel css--> --}}
<!-- Layout config Js -->
<script src="{{ URL::asset('assets/js/layout.js') }}"></script>
<!-- Bootstrap Css -->
<link href="{{ (Session::get('locale')=='ar') ?  URL::asset('assets/css/bootstrap.rtl.css') : URL::asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/css/button.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="{{ URL::asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="{{ (Session::get('locale')=='ar') ? URL::asset('assets/css/app.rtl.css') : URL::asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/css/app_css.css') }}" id="app-style" rel="stylesheet" type="text/css" />

<!--Bootstrap Select Styles -->

<!-- Styles -->
<link rel="stylesheet" href="{{ URL::asset('assets/libs/select2/css/select2.min.css') }}">

<!-- custom Css-->
<link href="{{ (Session::get('locale')=='ar') ? URL::asset('assets/css/custom.min.css') : URL::asset('assets/css/custom.css') }}" id="app-style" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link rel="stylesheet" href={{asset("assets/css/dropify.css")}}>
<style>
:root {
  --vz-body-bg: #38B7FE17;
  }
</style>


<script src="{{url('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{url('assets/libs/simplebar/simplebar.min.js')}}"></script>
<script src="{{url('assets/libs/node-waves/waves.min.js')}}"></script>
<script src="{{url('assets/libs/feather-icons/feather.min.js')}}"></script>
<script src="{{url('assets/js/pages/plugins/lord-icon-2.1.0.js')}}"></script>
<script src="{{url('assets/js/plugins.js')}}"></script>

<!-- apexcharts -->
<script src="{{url('assets/libs/apexcharts/apexcharts.min.js')}}"></script>

<!-- Dashboard init -->
<script src="{{url('assets/js/pages/dashboard-crm.init.js')}}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
 <script>
    
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
</script>

<!-- App js -->
{{-- <script src="{{url('assets/js/app.js')}}"></script> --}}
@yield('script')
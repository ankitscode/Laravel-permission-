@extends('layout.layout')
@section('css')
    <link href="{{ URL::asset('assets/libs/jsvectormap/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/libs/swiper/swiper.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .bg-user {
            background: #FF9702 0% 0% no-repeat padding-box !important;
            opacity: 1;
        }

        .bg-product {
            background: #00D683 0% 0% no-repeat padding-box !important;
            opacity: 1;
        }

        .bg-package {
            background: #FF142B 0% 0% no-repeat padding-box !important;
            opacity: 1;
        }

        .bg-doctor {
            background: #38B7FE 0% 0% no-repeat padding-box !important;
            opacity: 1;
        }

        .bg-revenue {
            background: #4769CA 0% 0% no-repeat padding-box !important;
            opacity: 1;
        }
        .card.header {
            width: auto;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            margin-bottom: 20px;
        }

        .card.header h4 {
            margin-bottom: 15px;
        }

        .table-responsive {
            margin-top: 20px;
        }

        .table thead {
            background-color: #343a40;
            color: white;
        }

        .table tbody tr:hover {
            background-color: #f1f1f1;
        }

        .img-thumbnail {
            border-radius: 50%;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-6 col-md-6">
            <!-- card -->
            <div class="card card-animate bg-user">
                <div class="card-body">
                    <div class="row d-flex align-items-center">
                        <div class="col-4">
                            <img src="{{ asset('assets/images/total_users.svg') }}" alt="usericon">
                        </div>
                        <div class="col-8">
                            <p class="text-uppercase fw-bold text-white text-truncate mb-0">{{ __('Number of users') }}</p>
                            <h4 class="fs-22 fw-bold ff-secondary text-white mb-4"><span  id="total_users" class="counter-value"
                                    data-target="{{ isset($count) ? $count : 0 }}">{{ $count }}</span></h4>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div>
        <div class="col-xl-6 col-md-6">
            <!-- card -->
            <div class="card card-animate bg-doctor">
                <div class="card-body">
                    <div class="row d-flex align-items-center">
                        <div class="col-4">
                            <img src="{{ asset('assets/images/total_users.svg') }}" alt="usericon">
                        </div>
                        <div class="col-8">
                            <p class="text-uppercase fw-bold text-white text-truncate mb-0">{{ __('Number of Doctors') }}</p>
                            <h4 class="fs-22 fw-bold ff-secondary text-white mb-4"><span id="#total_doctors" class="counter-value"
                                    data-target="{{ isset($countDoctors) ? $countDoctors : 0 }}">{{ $countDoctors }}</span></h4>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div>
    </div>
    <div class="py-5">
    <div class="card header">
        <div class="card-body">
            <h4 class="card-title mb-4">List of Latest Users</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle mb-0">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col" style="width: 20%">Name</th>
                            <th scope="col" style="width: 20%">Email</th>
                            <th scope="col" style="width: 20%">Petname</th>
                            <th scope="col" style="width: 20%">Breed</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($latestUsers as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->petname }}</td>
                                <td>{{ $user->breed }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        function refresh() {
            $.ajax({
                url: '{{ route('refreshdashboard') }}',
                method: "GET",
                success: function(data) {
                    console.log(data);
                    $('#total_users').text(data?.count);
                    $('#total_doctors').text(data?.countDoctors);
                },
                error: function(error) {
                    console.log('Error fetching data:', error);
                }
            });
        }

        $(document).ready(function() {
            setInterval(function() {
                refresh();
            }, 5000);
        });
    </script>
@endsection

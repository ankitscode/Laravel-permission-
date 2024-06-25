@extends('layout.layout')
@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4 h-100">
            <div class="card-header py-3 d-flex align-items-center">
                <h6 class="m-0 font-weight-bold text-primary flex-grow-1">{{__('main.user_details')}}</h6>
                <div class="flex-shrink-0">
                    {{-- <a @if($userDetails->uuid === Auth::user()->uuid) href="javascript:void(0)" class="btn btn-disable" @disabled(true) @else class="btn btn-primary edit-item-btn" href="{{route('admin.editUser',['uuid'=>$userDetails->uuid])}}" @endif ><i class="ri-edit-line fs-16"></i></a>

                    <a href="javascript:void(0)" @if($userDetails->uuid === Auth::user()->uuid) class="btn btn-disable" @disabled(true) @else class="btn btn-danger remove-item-btn" data-id="{{$userDetails->uuid}}" @endif
                     ><i class="ri-delete-bin-2-line fs-16"></i></a> --}}
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <td class="font-bold-600" style="width: 25%;">{{ __('main.full_name') }}</td>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <td class="font-bold-600" style="width: 25%;">{{ __('main.email') }}</td>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <td class="font-bold-600" style="width: 25%;">{{ __('Pet Name') }}</td>
                                <td>{{ $user->petname }}</td>
                            </tr>
                            <tr>
                                <td class="font-bold-600">{{ __('Pet Breed') }}</td>
                                <td>{{ ($user->breed) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow mb-4 h-100">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{__('main.profile_picture')}}</h6>
            </div>
            <div class="card-body">
                <div class="card-body text-center">
                    <img class="rounded-circle mb-2 avater-ext" src="{{!empty($username) ? asset(config('image.profile_image_path_view').$user>-name): asset("assets/images/users/user-dummy-img.jpg")}}" style="height: 10rem;width: 10rem;">
                    <div class="large text-muted mb-4">
                        <span class="badge rounded-pill badge-outline-{{$user->is_active==1?'success':'danger'}}">
                            {{$user->is_active ?  __('main.active')  :  __('main.in_active') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('.dropify').dropify();
        });
    </script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('body').on('click', '.remove-item-btn', function(e) {
                e.preventDefault();
                var id = $(this).data("id");
                swal({
                        title: "Are you sure?",
                        text: "Once deleted, you will not be able to recover this user!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            var data = {
                                "_token": $('a[name="csrf-token"]').val(),
                                "id": id,
                            }
                            $.ajax({
                                type: "DELETE",
                                url: "{{ route('deleteuser', '') }}" + "/" + id,
                                data: data,
                                success: function(response) {
                                    swal(response.status, {
                                            icon: "success",
                                            timer: 3000,
                                        })
                                        .then((result) => {
                                            window.location =
                                                '{{ route('userindex') }}'
                                        });
                                }
                            });
                        }
                    });
            });
        });
    </script>
@endsection

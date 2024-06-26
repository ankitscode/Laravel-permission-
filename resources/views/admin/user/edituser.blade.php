@extends('layout.layout')
@section('content')
    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{__('main.profile_picture')}}</h6>
                </div>
                <div class="card-body">
                    <div class="card-body text-center">
                        <img class="rounded-circle mb-2 avater-ext" src="{{ asset('storage/images/' . $user->image) }}" alt="Profile Image" style="height: 10rem;width: 10rem;">
                        <div class="large text-muted mb-4">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{__('Edit Profile')}}</h6>
                </div>
                <div class="card-body">
                    @include('components.display_alert_message') 
                    <form action="{{ route('userupdate') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ $user->id }}">
                        <div class="py-1">
                            <label for="image" class="form-label">Profile</label>
                            <div class="d-flex align-items-center">
                                <input type="file" class="form-control" name="image" accept="image/*">
                            </div>
                            <span class="text-danger">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </span>
                        </div>
                            <div class="py-1">
                                <label for="" class="from-label">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" required value="{{ $user->name }}">
                                <span class="text-danger">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </span>
                            </div>
                            <div class="py-1">
                                <label for="" class="from-label">Email</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="email" name="email" required value="{{ $user->email }}">
                                <span class="text-danger">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </span>
                            </div>
                            <button type="submit" class="btn btn-primary mb-2" id="formsubmit">Add</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

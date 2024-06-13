@extends('layout.layout')
@section('css')
    <link href="{{ URL::asset('assets/libs/jsvectormap/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/libs/swiper/swiper.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="{{ asset('assets/libs/data-tables/datatables.min.css') }}" href="style.css"> 
    <link rel="stylesheet" href="{{ asset('datatable_styles.css') }}">
    <style>

        .modal-body {
            max-height: calc(100vh - 200px);
            overflow-y: auto;
        }
       
    </style>
@endsection
@section('content')
    <div class="mt-3">
        <div id="layout-wrapper">
            <div class="col-lg-12">
                <div class="card ">
                    <div class="card-header align-items-center d-flex">
                        <h5 class="card-title mb-0 flex-grow-1">Your User details here</h5>
                        <div class="flex-shrink">
                            <button type="button" id="modalButton" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                Add User
                            </button>
                        </div>
                      </div>
                    <!-- end card header -->
                    @php
                        $columns = [
                            ['name' => 'Name', 'width' => '10%'],
                            ['name' => 'Email', 'width' => '10%'],
                            ['name' => 'Pet Name', 'width' => '10%'],
                            ['name' => 'Breed', 'width' => '10%'],
                            ['name' => 'Created_at', 'width' => '20%'],
                            ['name' => 'Updated_at', 'width' => '20%'],
                            ['name' => 'Action', 'width' => '20%'],
                        ];
                    @endphp

                    <x-datatabel id="Table" :columns="$columns" />

                    <!-- end card body -->
                </div>
                <!-- end card -->

                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">User</h5>
                                <button id="add" type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    @csrf
                                    <input type="hidden" name="id" value="">
                                    <div class="py-3">
                                        <label for="" class="from-label">Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" required>
                                        <span class="text-danger">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </span>
                                    </div>
                                    <div class="py-3">
                                        <label for="" class="from-label">Email</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="email" name="email" required>
                                        <span class="text-danger">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </span>
                                    </div>
                                    <div class="py-3">
                                        <label for="" class="from-label">Pet Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="petname" name="petname" required>
                                        <span class="text-danger">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </span>
                                    </div>
                                    <div class="py-3">
                                        <label for="" class="form-label">breed</label>
                                        <input type="text" class="form-control @error('degree') is-invalid @enderror"
                                            id="breed" name="breed" required>
                                        <span class="text-danger">
                                            @error('breed')
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
                <!-- end modal -->
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{ asset('assets/libs/data-tables/datatables.min.js') }}"></script>
    {{-- <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script> --}}
    <script>
        $('#Table').DataTable({
            'paging': true,
            'lengthChange': true,
            'lengthMenu': [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ],
            'searching': true,
            'ordering': true,
            'info': true,
            'autoWidth': false,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{!! route('datatable.index') !!}",
                "type": "GET",
                "data": function(d) {
                    d.filterSearchKey = $("#filter_search_key").val();
                }
            },
            "columns": [{
                    "data": "name",
                    "render": function(data, type, row) {
                        return data;
                    },
                },
                {
                    "data": "email",
                    "render": function(data, type, row) {
                        return data;
                    },
                },
                {
                    "data": "petname",
                    "render": function(data, type, row) {
                        return data;
                    },
                },
                {
                    "data": "breed",
                    "render": function(data, type, row) {
                        return data;
                    },
                },
                {
                    "data": "created_at",
                    "render": function(data, type, row) {
                        return data;
                    },
                },
                {
                    "data": "updated_at",
                    "render": function(data, type, row) {
                        return data;
                    },
                },
                {
                    "data": "Action",
                    "render": function(data, type, row) {
                        return data;
                    },
                },
            ],
            'columnDefs': [{
                    "targets": 0,
                    'searchable': true,
                    'orderable': true,
                    'width': '30%'
                },
                {
                    "targets": 1,
                    'searchable': true,
                    'orderable': true,
                    'width': '30%'
                },
                {
                    "targets": 2,
                    'searchable': true,
                    'orderable': true,
                    'width': '30%'
                }
            ],
            "order": [
                [1, 'desc']
            ]
        });

        $('#deleteButton').click(function() {
            deleteUser(id);
        });

        $('#modalButton').click(function(e) {
            $('#name').val('');
            $('#email').val('');
            $('#petname').val('');
            $('#breed').val('');
        });


        $("#formsubmit").on("click", function(e) {
            e.preventDefault();
            const functionToRun = $(this).html();
            if (functionToRun == "Add") {
                addUser();
            } else {
                const id = $(this).attr("data-id");
                updateUser(id);
            }
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function addUser() {
            var name = $('#name').val();
            var email = $('#email').val();
            var petname = $('#petname').val();
            var breed = $('#breed').val();
            $.ajax({
                type: "POST",
                url: '{{ route('adduser') }}',
                data: {
                    'name': name,
                    'email': email,
                    'petname': petname,
                    'breed': breed,
                    '_token': '{{ csrf_token() }}'
                },
                success: function(data) {
                    $('#exampleModal').modal('hide');
                    $('#name').val('');
                    $('#email').val('');
                    $('#petname').val('');
                    $('#breed').val('');
                    $('#Table').DataTable().ajax.reload();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }


        function deleteUser(id) {
            if (confirm('Are you sure you want to delete this user?')) {
                $.ajax({
                    url: '{{ route('deleteuser', ':id') }}'.replace(':id', id),
                    method: 'GET',
                    data: {
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#Table').DataTable().ajax.reload();
                    }
                });
            }
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function editUser(id) {
            $.ajax({
                url: '{{ route('edituser', ':id') }}'.replace(':id', id),
                method: 'GET',
                success: function(data) {
                    $('#name').val(data.name);
                    $('#email').val(data.email);
                    $('#petname').val(data.petname);
                    $('#breed').val(data.breed);
                    $('#exampleModal').modal('show');
                    $("#formsubmit").html("Update");
                    $("#formsubmit").attr("data-id", id);
                }
            });
        }

        function updateUser(id) {
            var name = $('#name').val();
            var email = $('#email').val();
            var petname = $('#petname').val();
            var breed = $('#breed').val();
            $.ajax({
                type: "POST",
                url: '{{ route('userupdate', '') }}/' + id,
                data: {
                    'name': name,
                    'email': email,
                    'petname': petname,
                    'breed': breed,
                    '_token': '{{ csrf_token() }}'
                },
                success: function(data) {
                    $('#exampleModal').modal('hide');
                    $('#name').val('');
                    $('#email').val('');
                    $('#petname').val('');
                    $('#breed').val('');
                    $('#Table').DataTable().ajax.reload();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }
    </script>
@endsection

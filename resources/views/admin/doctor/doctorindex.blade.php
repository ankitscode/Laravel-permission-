@extends('layout.layout')
@section('css')
<style>
     .modal-body {
        max-height: calc(100vh - 200px); /* Adjust the value based on your layout */
        overflow-y: auto;
        } */
    </style>
<link rel="stylesheet" href="{{ asset('datatable_styles.css') }}">
    <link rel="{{ asset('assets/libs/data-tables/datatables.min.css') }}" href="style.css">
@endsection
@section('content')
    <div class="mt-3">
        <div id="layout-wrapper">
            <div class="col-xxl-12">
                <div class="card card-height-80">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Add Doc</h4>
                        <div class="flex-shrink">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                Add
                            </button>
                        </div>
                    </div>
                    @php
                        $columns = [
                            ['name' => 'Name', 'width' => '10%'],
                            ['name' => 'Degree', 'width' => '10%'],
                            ['name' => 'Email', 'width' => '20%'],
                            ['name' => 'Treatment', 'width' => '20%'],
                            ['name' => 'Patient Name', 'width' => '20%'],
                            ['name' => 'Created at', 'width' => '20%'],
                            ['name' => 'Updated at', 'width' => '20%'],
                            ['name' => 'Action', 'width' => '20%'],
                        ];
                    @endphp
                    <!-- end card header -->
                    <x-datatabel id="table" :columns="$columns" /> <!--include component here-->

                    <tbody>

                    </tbody>

                    <!-- end table -->
                </div>
                <!-- end table responsive -->
            </div>
            <!-- end card body -->
        </div>
        <!-- end card -->
        <!-- Button trigger modal -->
        <!-- Modal -->
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Doc</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            @csrf
                            <div class="py-3">
                                <label for="" class="from-label">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}" required>
                                <span class="text-danger">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </span>
                            </div>
                            <div class="py-3">
                                <label for="" class="from-label">Email</label>
                                <input type="text" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}" required>
                                <span class="text-danger">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </span>
                            </div>
                            <div class="py-3">
                                <label for="" class="form-label">Degree</label>
                                <input type="text" class="form-control @error('degree') is-invalid @enderror"
                                    id="degree" name="degree" value="{{ old('deegree') }}" required>
                                <span class="text-danger">
                                    @error('breed')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </span>
                            </div>
                            <div class="py-3">
                                <label for="" class="form-label">Treatment</label>
                                <input type="text" class="form-control @error('treatment') is-invalid @enderror"
                                    id="treatment" name="treatment" value="{{ old('treatment') }}" required>
                                <span class="text-danger">
                                    @error('treatment')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </span>
                            </div>
                            <div class="py-3">
                                <label for="" class="form-label">Patientname</label>
                                <input type="text" class="form-control @error('patient_name') is-invalid @enderror"
                                    id="patient_name" name="patient_name" value="{{ old('patient_name') }}" required>
                                <span class="text-danger">
                                    @error('patient_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </span>
                            </div>
                            {{-- <div class="mb-2">
                                <label for="userpassword" class="form-label">Password <span
                                        class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    name="password" id="password" placeholder="" required >
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <div class="invalid-feedback">
                                    Please enter password
                                </div>
                            </div> --}}
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
<script src="{{ asset("assets/libs/data-tables/datatables.min.js") }}"></script>
    <script>
        $('#table').DataTable({
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
                "url": "{!! route('datatabelDoctor') !!}",
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
                    "data": "degree",
                    "render": function(data, type, row) {
                        return data;
                    },
                },
                {
                    "data": "treatment",
                    "render": function(data, type, row) {
                        return data;
                    },
                },
                {
                    "data": "patient_name",
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
            deleteDoctor(id);
        });

        $('#modalButton').click(function(e) {
            $('#name').val('');
            $('#email').val('');
            $('#degree').val('');
            $('#treatment').val('');
            $('#patient_name').val('');;
        });


        $("#formsubmit").on("click", function(e) {
            e.preventDefault();
            const functionToRun = $(this).html();
            if (functionToRun == "Add") {
                addDoctor();
            } else {
                const id = $(this).attr("data-id");
                updateDoctor(id);
            }
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function addDoctor() {
            var name = $('#name').val();
            var email = $('#email').val();
            var degree = $('#degree').val();
            var treatment = $('#treatment').val();
            var patient_name = $('#patient_name').val();
            var password = $('#password').val();

            $.ajax({
                type: "POST",
                url: '{{ route('createdoctor') }}',
                data: {
                    'name': name,
                    'email': email,
                    'degree': degree,
                    'treatment': treatment,
                    'patient_name': patient_name,
                    // 'password': password,
                    '_token': '{{ csrf_token() }}'
                },
                success: function(data) {
                    $('#exampleModal').modal('hide');
                    $('#name').val('');
                    $('#email').val('');
                    $('#degree').val('');
                    $('#treatment').val('');
                    $('#patient_name').val('');
                    // $('#password').val('');
                    $('#table').DataTable().ajax.reload();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }


        function deleteDoctor(id) {
            if (confirm('Are you sure you want to delete this doctor?')) {
                $.ajax({
                    url: '{{ route('deletedoctor', ':id') }}'.replace(':id', id),
                    method: 'GET',
                    data: {
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#table').DataTable().ajax.reload();
                    }
                });
            }
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function editDoctor(id) {
            $.ajax({
                url: '{{ route('editdoctor', ':id') }}'.replace(':id', id),
                method: 'GET',
                success: function(data) {
                    $('#name').val(data.name);
                    $('#email').val(data.email);
                    $('#degree').val(data.degree);
                    $('#treatment').val(data.treatment);
                    $('#patient_name').val(data.patient_name);
                    // Password field might not be pre-filled for security reasons
                    // $('#password').val(data.password);
                    $('#exampleModal').modal('show');
                    $("#formsubmit").html("Update");
                    $("#formsubmit").attr("data-id", id);
                }
            });
        }

        function updateDoctor(id) {
            var name = $('#name').val();
            var email = $('#email').val();
            var degree = $('#degree').val();
            var treatment = $('#treatment').val();
            var patient_name = $('#patient_name').val();
            var password = $('#password').val();
            $.ajax({
                type: "POST",
                url: '{{ route('updatedoctor', '') }}/' + id,
                data: {
                    'name': name,
                    'email': email,
                    'degree': degree,
                    'treatment': treatment,
                    'patient_name': patient_name,
                    '_token': '{{ csrf_token() }}'
                },
                success: function(data) {
                    $('#exampleModal').modal('hide');
                    $('#name').val('');
                    $('#email').val('');
                    $('#degree').val('');
                    $('#treatment').val('');
                    $('#patient_name').val('');
                    $('#table').DataTable().ajax.reload();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }
    </script>
@endsection

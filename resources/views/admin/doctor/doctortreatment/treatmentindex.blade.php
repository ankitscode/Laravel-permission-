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
                        <h5 class="card-title mb-0 flex-grow-1">Treatment Details</h5>
                        <div class="flex-shrink">
                            <button type="button" id="modalButton" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                Add
                            </button>
                        </div>
                    </div>
                    <!-- end card header -->
                    @php
                        $columns = [
                            ['name' => 'Doctor Name', 'width' => '10%'],
                            ['name' => ' Pet Name', 'width' => '10%'],
                            ['name' => 'treatment', 'width' => '10%'],
                            ['name' => 'note', 'width' => '20%'],
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
                                <h5 class="modal-title" id="exampleModalLabel"></h5>
                                <button id="add" type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    @csrf
                                    <input type="hidden" name="id" value="">
                                    <div class="py-3">
                                        <label for="doc_id" class="form-label">Doctor Name</label>
                                        <select class="form-select @error('Doctor name') is-invalid @enderror" id="doc_id"
                                            name="doc_id" required>
                                            <option value="">Select doctor Name</option>
                                            @foreach ($Doctors as $doctor)
                                                <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger">
                                            @error('doc_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </span>
                                    </div>
                                    <div class="py-3">
                                        <label for="pet_id" class="form-label">Pet Name</label>
                                        <select class="form-select @error('Pet Name') is-invalid @enderror" id="pet_id"
                                            name="pet_id" required>
                                            <option value="">Select Pet Name</option>
                                            @foreach ($Pets as $pet)
                                                <option value="{{ $pet->id }}">{{ $pet->petname }}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger">
                                            @error('user_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </span>
                                    </div>
                                    <div class="py-3">
                                        <label for="treatment" class="from-label">Treatment</label>
                                        <input type="text" class="form-control @error('treatment') is-invalid @enderror"
                                            id="treatment" name="treatment" required>
                                        <span class="text-danger">
                                            @error('treatment')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </span>
                                    </div>
                                    <div class="py-3">
                                        <label for="note" class="form-label">Note</label>
                                        <input type="text" class="form-control @error('note') is-invalid @enderror"
                                            id="note" name="note" required>
                                        <span class="text-danger">
                                            @error('note')
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
            'processing': true,
            'serverSide': true,
            'ajax': {
                'url': '{!! route('treatmentTablelist') !!}',
                'type': 'GET',
                'data': function(d) {
                    d.filterSearchKey = $('#filter_search_key').val();
                }
            },
            'columns': [{
                    'data': 'doctor_name',
                    'name': 'doctors.name'
                },
                {
                    'data': 'petname',
                    'name': 'pets.petname'
                },
                {
                    'data': 'treatment',
                    'name': 'treatments.treatment'
                },
                {
                    'data': 'note',
                    'name': 'Treatments.note'
                },
                {
                    'data': 'created_at',
                    'name': 'treatments.created_at'
                },
                {
                    'data': 'updated_at',
                    'name': 'treatments.updated_at'
                },
                {
                    'data': 'Action',
                    'name': 'Action',
                    'orderable': false,
                    'searchable': false
                }
            ],
            'columnDefs': [{
                'targets': [0, 1, 2, 3, 4],
                'width': '20%'
            }],
            'order': [
                [1, 'desc']
            ] // Assuming sorting by petname, adjust as per your requirement
        });

        $('#deleteButton').click(function() {
            deleteTreatment(id);
        });

        $('#modalButton').click(function(e) {
            $('#doc_id').val('');
            $('#pet_id').val('');
            $('#treatment').val('');
            $('#note').val('');

        });


        $("#formsubmit").on("click", function(e) {
            e.preventDefault();
            const functionToRun = $(this).html();
            if (functionToRun == "Add") {
                addTreatment();
            } else {
                const id = $(this).attr("data-id");
                updateTreatment(id);
            }
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function addTreatment() {
            var doc_id = $('#doc_id').val();
            var pet_id = $('#pet_id').val();
            var treatment = $('#treatment').val();
            var note = $('#note').val();
            // Check if a file was selected
            $.ajax({
                type: "POST",
                url: '{{ route('createTreatment') }}',
                data: {
                     'doc_id':  doc_id,
                     'pet_id': pet_id, 
                     'treatment': treatment,
                      'note': note, 
                    '_token': '{{ csrf_token() }}'
                },
                success: function(data) {
                    $('#exampleModal').modal('hide');
                    $('#doc_id').val('');
                    $('#pet_id').val('');
                    $('#treatment').val('');
                    $('#note').val('');
                    $('#Table').DataTable().ajax.reload();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });

        }


        function deleteTreatment(id) {

            if (confirm('Are you sure you want to delete this ?')) {
                $.ajax({
                    url: '{{ route('deleteTreatment', ':id') }}'.replace(':id', id),
                    method: 'Get',
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

        function editTreatment(id) {
            $.ajax({
                url: '{{ route('editTreatment', ':id') }}'.replace(':id', id),
                method: 'GET',
                success: function(data) {
                    $('#doc_id').val(data.doc_id);
                    $('#pet_id').val(data.pet_id);
                    $('#treatment').val(data.treatment);
                    $('#note').val(data.note);
                    $('#exampleModal').modal('show');
                    $("#formsubmit").html("Update");
                    $("#formsubmit").attr("data-id", id);
                }
            });
        }

        function updateTreatment(id) {
            var doc_id = $('#doc_id').val();
            var pet_id = $('#pet_id').val();
            var treatment = $('#treatment').val();
            var note = $('#note').val();
            $.ajax({
                type: "POST",
                url: '{{ route('updateTreatment', '') }}/' + id,
                data: {
                     'doc_id': doc_id,
                     'pet_id': pet_id,
                     'treatment':treatment,
                      'note':note,
                      '_token': '{{ csrf_token() }}'
                },
                success: function(data) {
                    $('#exampleModal').modal('hide');
                    $('#doc_id').val('');
                    $('#pet_id').val('');
                    $('#treatment').val('');
                    $('#note').val('');
                    $('#Table').DataTable().ajax.reload();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }
    </script>
@endsection

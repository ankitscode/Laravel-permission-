@extends('layout.layout')
@section('content')
    <div class="row mt-3">
        <div id="layout-wrapper">
            <div class="col-xxl-12">
                <div class="card card-height-80">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Add Doc Role</h4>
                        <div class="flex-shrink">
                            <a href="{{ route('doctor.createRole') }}"class="btn btn-primary">
                                Add
                            </a>
                        </div>
                    </div>
                    @php
                        $columns = [['name' => 'Role', 'width' => '90%'], ['name' => 'Action', 'width' => '10%']];
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
    @endsection
    @section('script')
        <script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
        <script>
            $('#table').DataTable({
                'paging': false,
                'lengthChange': true,
                'lengthMenu': [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ],
                'searching': false,
                'ordering': true,
                'info': true,
                'autoWidth': false,
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{!! route('dataTable.dataTableRoles') !!}",
                    "type": "GET",
                    "data": function(d) {
                        d.filterSearchKey = $("#filter_search_key").val();
                    }
                },
                "columns": [{
                        "data": "Role",
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
                ],
                "order": [
                    [1, 'desc']
                ]
            });
         </script>
    @endsection

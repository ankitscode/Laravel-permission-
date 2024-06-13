@extends('layout.layout')
{{-- @section('title')
    {{__('main.users')}}
@endsection --}}
@section('css')
<link href="{{ URL::asset('assets/libs/jsvectormap/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/libs/swiper/swiper.min.css') }}" rel="stylesheet" type="text/css" />
<!--datatable css-->
<link rel="{{ asset('assets/libs/data-tables/datatables.min.css') }}" href="style.css">
@endsection
@section('content')
    {{-- @component('components.breadcrumb')
@slot('li_1') {{__('main.index')}} @endslot
@slot('title') {{__('main.roles')}} @endslot
@slot('link') {{route('admin.roleList')}} @endslot
@endcomponent --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm" id="customerList">
                <div class="card-header border-bottom-dashed">
                    <div class="row g-4 align-items-center">
                        <div class="col-sm">
                            <div>
                                <h5 class="card-title mb-0">{{ __('Roles list') }}</h5>
                            </div>
                        </div>
                        @can('Create Role')
                            <div class="col-sm-auto">
                                <div>
                                    <a href="{{ route('admin.createRole') }}" class="btn btn-primary btn-sm" id="create-btn"><i
                                            class="ri-add-line align-bottom me-1"></i>{{ __('Create Role') }}</a>
                                </div>
                            </div>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive table-card mb-1">
                        <table class="table nowrap align-middle w-100" style="margin-top: 0px !important;" id="roleTable">
                            <thead class="table-light text-muted">
                                <tr>
                                    <th class="sort">{{ __('Name') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="list form-check-all">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @can('Delete User')
        <form id="deleteRecordForm" method="post">
            @csrf
        </form>
    @endcan
@endsection
@section('script')
<script src="{{ asset("assets/libs/data-tables/datatables.min.js") }}"></script>
    <script>
        $(document).ready(function() {
            $('#roleTable').DataTable({
                'paging': false,
                'lengthChange': false,
                'searching': false,
                'ordering': true,
                'info': true,
                'autoWidth': false,
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{!! route('dataTable.dataTableRolesListTable') !!}",
                    "type": "GET"
                },
                "columns": [{
                        "data": "name",
                        "render": function(data, type, row) {
                            @if (auth()->user()->can('View Role Details'))
                                return '<a href="{{ route('admin.showRole', '') }}/' + row.id +
                                    '" class="text-primary" style="text-decoration: none !important;">' +
                                    data + '</a>';
                            @else
                                return data;
                            @endif
                        },
                    },
                    {
                        "data": "id",
                        "render": function(data, type, row) {
                            return '@canany(['View Role Details', 'Edit Role', 'Delete'])<ul class="list-inline hstack gap-2 mb-0">@can('View Role Details')<li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="{{ __('main.view') }}"><a href="{{ route('admin.showRole', '') }}/' +
                                data +
                                '" class="text-success d-inline-block"><i class="ri-eye-fill fs-16"></i></a></li>@endcan @can('Edit Role')<li class="list-inline-item edit" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="{{ __('main.edit') }}"><a href="{{ route('admin.editRole', '') }}/' +
                                data +
                                '" class="text-primary d-inline-block edit-item-btn" onclick="deleteRecord($id)"><i class="ri-edit-2-fill fs-16"></i></a></li>@endcan @can('Delete Role')<li class="list-inline-item delete" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="{{ __('main.delete') }}"><a href=""/' +
                                data +
                                '" class="text-danger d-inline-block delete-item-btn"><i class="ri-delete-bin-2-fill fs-16"></i></a></li>@endcan</ul>@endcan';
                        },
                    }

                ],
                'columnDefs': [{
                    "targets": 1,
                    "width": "15%",
                    'searchable': false,
                    'orderable': false,
                }],
                language: {
                    url: "@if (Auth::user()->lang == 'ar') {{ asset('assets/js/arabic.json') }} @elseif (Auth::user()->lang == 'de') {{ asset('assets/js/german.json') }}  @endif"
                }
            });
        });

        // Function to handle deletion
        @can('Delete User')
            function deleteRecord(element) {
                Swal.fire({
                    title: "{{ __('message.are_you_sure') }}",
                    text: "{{ __('message.you_will_not_be_able_to_recover_data') }}",
                    showCancelButton: true,
                    confirmButtonClass: 'btn btn-primary w-xs me-2 mb-1',
                    cancelButtonClass: 'btn btn-danger w-xs mb-1',
                    confirmButtonText: "{{ __('message.delete_it') }}",
                    buttonsStyling: false,
                }).then((result) => {
                    if (result.isConfirmed) {
                        var href = $(element).attr('data-href');
                        $("#deleteRecordForm").attr("action", href);
                        $('#deleteRecordForm').submit();
                    }
                })
            }
        @endcan
    </script>
@endsection

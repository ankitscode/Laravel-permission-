@section ('css')
<link rel="stylesheet" href="{{ asset('datatable_styles.css') }}">
@endsection
<div class="card-body">
    <div class="table-responsive table-card">
        <table class="table table-centered align-middle table-custom-effect table-nowrap mb-0"
        style="width:100%;margin-top: 0px !important;" id="{{ $id }}">
        <thead class="table-light">
            <tr>
                @foreach ($columns as $column)
                    <th style="width: {{ $column['width'] ?? 'auto' }}">{{ __($column['name']) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>

        </tbody>
        </table>
</div> 
{{-- @section('css')
@endsection
<div class="row">
<div class="col-lg-12">
        <div class="card-body">
            <table id="{{ $id }} ,ajax-datatables" class="display table table-bordered dt-responsive" style="width: 100%">
                <thead>
                    <tr>
                        @foreach ($columns as $column)
                            <th style="width: {{ $column['width'] ?? 'auto' }}">{{ __($column['name']) }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                </tbody>        
            </table>
        </div>
</div>
</div> --}}


{{-- slot way --}}
{{-- {{ $slot }}  --}}
{{-- <th style="width: 30%">{{ __('$Name') }}</th>
<th style="width: 30%">{{ __('$Degree') }}</th>
<th style="width: 30%">{{ __('Phone Number') }}</th>
<th style="width: 10%">{{ __('Action') }}</th> --}}

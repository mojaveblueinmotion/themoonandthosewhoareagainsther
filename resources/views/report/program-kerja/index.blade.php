@php
    $uri = request()->route()->uri;
    $menu = explode('/', $uri)[2] ?? '';
@endphp
@extends('layouts.lists')

@section('filters')
    <div class="row">
        <div class="col-12 col-sm-6 col-md-2 mr-n6">
            <input type="text" class="form-control filter-control base-plugin--datepicker-3" data-post="year"
                placeholder="{{ __('Tahun') }}">
        </div>
    </div>
@endsection

@section('buttons')
    {{-- @if (auth()->user()->checkPerms($perms . '.create'))
		@include('layouts.forms.btnAdd')
	@endif --}}
@endsection

@push('scripts')
    <script>
        $(function() {
            $('.content-page')
                .on('change', 'select.filter-control.filter-type-laporan', function(e) {
                    var me = $(this);
                    if (me.val()) {
                        BaseContent.replaceByUrl("{{ url('report/program-kerja') }}/" + this.value);
                    }
                    BasePlugin.initSelect2();
                });
        });
    </script>
@endpush

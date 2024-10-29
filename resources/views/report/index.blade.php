@php
    $uri = request()->route()->uri;
    $menu = explode('/', $uri)[2] ?? '';
@endphp
@extends('layouts.lists')

@section('filters')
    <div class="row">
        <div class="col-12 col-sm-6 col-md-4 mr-n6">
            <select class="form-control base-plugin--select2-ajax filter-control" data-post="type"
                data-placeholder="{{ __('Tipe Laporan') }}">
                <option value="" selected>{{ __('Tipe Laporan') }}</option>
                @foreach ($TYPE as $key => $value)
                    <option value="{{ $key }}">{{ __($value['show']) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-sm-6 col-md-2 mr-n6">
            <input type="text" class="form-control filter-control base-plugin--datepicker-3" data-post="year"
                placeholder="{{ __('Tahun') }}">
        </div>
        <div class="col-12 col-sm-6 col-md-2 mr-n6">
            <select class="form-control filter-control base-plugin--select2 filter-category" data-post="category"
                placeholder="{{ __('Kategori') }}">
                <option value="" selected>{{ __('Semua Kategori') }}</option>
                <option value="operation">{{ __('PKPT') }}</option>
                <option value="special">{{ __('Non PKPT') }}</option>
                <option value="ict">{{ __('TI') }}</option>
            </select>
        </div>
        <div class="col-12 col-sm-6 col-md-2 mr-n6">
            <select class="form-control filter-control base-plugin--select2-ajax filter-object" data-post="object"
                data-url="{{ route('ajax.selectObject', ['category' => '']) }}"
                data-url-origin="{{ route('ajax.selectObject') }}" placeholder="{{ __('Subject Audit') }}">
                <option value="">{{ __('Semua Subjek Audit') }}</option>
            </select>
        </div>
        <div class="col-12 col-sm-6 col-md-2 mr-n6">
            <input type="text" name="schedule" class="form-control base-plugin--datepicker"
                placeholder="{{ __('Tanggal') }}" data-orientation="bottom" data-options='@json([
                    'startDate' => null,
                    'endDate' => null,
                ])'>
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
                        BaseContent.replaceByUrl("{{ url('report/training') }}/" + this.value);
                    }
                    BasePlugin.initSelect2();
                });
        });
    </script>
@endpush

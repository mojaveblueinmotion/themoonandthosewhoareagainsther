@php
    $uri = request()->route()->uri;
    $menu = explode('/', $uri)[2] ?? '';
@endphp
@extends('layouts.lists')

@section('filters')
    <div class="row">
        <div class="col-12 col-sm-6 col-md-1 mr-n6">
            <input type="text" class="form-control filter-control base-plugin--datepicker-3 filter-year" data-post="year"
                placeholder="{{ __('Tahun') }}">
        </div>
        <div class="col-12 col-sm-6 col-md-2 mr-n6 pb-2">
            <select name="type_id" data-post="type_id"
                class="form-control filter-control base-plugin--select2-ajax filter-type-id"
                data-url="{{ rut('ajax.selectTypeAudit', ['search' => 'all']) }}" placeholder="{{ __('Jenis Audit') }}">
                <option value="">{{ __('Jenis Audit') }}</option>
            </select>
        </div>
        <div class="col-12 col-sm-6 col-xl-3 mr-n6 pb-2">
            <select data-post="object_id" class="form-control filter-control base-plugin--select2-ajax filter-object"
                disabled data-url-origin="{{ rut('ajax.selectStruct', ['search' => 'subject']) }}"
                placeholder="{{ __('Subject Audit') }}">
                <option value="">{{ __('Subject Audit') }}</option>
            </select>
        </div>
        <div class="col-12 col-sm-5 col-md-4 mr-n6 pb-2">
            <select class="form-control filter-control base-plugin--select2-ajax filter-auditor" data-post="auditor"
                data-url="{{ rut('ajax.selectUser', ['search' => 'auditor']) }}" placeholder="{{ __('Auditor') }}">
                <option value="">{{ __('Auditor') }}</option>
            </select>
        </div>
        <div class="col-12 col-sm-6 col-xl-3 mr-n6 pb-2">
            <select name="auditee_id" data-post="auditee_id"
                class="form-control filter-control base-plugin--select2-ajax unitKerja" id="unitKerja"
                data-url="{{ rut('ajax.selectStruct', 'department_auditee') }}" placeholder="{{ __('Dept. Auditee') }}">
                <option value="">{{ __('Dept. Auditee') }}</option>
            </select>
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
                        BaseContent.replaceByUrl("{{ url('report/pelaporan-audit') }}/" + this.value);
                    }
                    BasePlugin.initSelect2();
                })
                .on('change', 'select.filter-control.filter-type-id', function(e) {
                    var me = $(this);
                    if (me.val()) {
                        var objectId = $('select.filter-object');
                        var urlOrigin = objectId.data('url-origin');
                        var urlParam = $.param({
                            category: 'by_type',
                            type_id: me.val()
                        });
                        objectId.data('url', decodeURIComponent(decodeURIComponent(urlOrigin + '?' +
                            urlParam)));
                        objectId.val(null).prop('disabled', false);
                    }
                    BasePlugin.initSelect2();
                });
        });
    </script>
@endpush
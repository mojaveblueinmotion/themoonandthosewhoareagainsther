@php
    $uri = request()->route()->uri;
    $menu = explode('/', $uri)[2] ?? '';
@endphp
@extends('layouts.lists')

@section('filters')
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3 mr-n6">
            <select class="form-control base-plugin--select2-ajax filter-control filter-type-laporan" data-post="type"
                data-placeholder="{{ __('Tipe Laporan') }}">
                <option value="" selected>{{ __('Tipe Laporan') }}</option>
                @foreach (App\Http\Controllers\Report\ReportRiskAssessmentController::TYPE as $key => $value)
                    <option {{ $menu == $key ? 'selected' : '' }} value="{{ $key }}">{{ __($value['show']) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-sm-6 col-md-1 mr-n6">
            <input type="text" class="form-control filter-control base-plugin--datepicker-3 filter-year"
                data-post="periode" placeholder="{{ __('Tahun') }}" {{ $menu == '' ? 'disabled' : '' }}>
        </div>
        <div class="col-12 col-sm-6 col-md-3 mr-n6 pb-2">
            <select name="type_id" data-post="type_id"
                class="form-control filter-control base-plugin--select2-ajax filter-type-id"
                data-url="{{ route('ajax.selectTypeAudit', ['search' => 'all']) }}" placeholder="{{ __('Jenis ') }}"
                {{ $menu == '' ? 'disabled' : '' }}>
                <option value="">{{ __('Jenis ') }}</option>
            </select>
        </div>
        <div class="col-12 col-sm-6 col-xl-3 mr-n6 pb-2">
            <select data-post="object_id" id="subject_id"
                class="form-control filter-control base-plugin--select2-ajax filter-object"
                data-url="{{ rut('ajax.selectStruct', ['search' => 'subject', 'type_id' => '']) }}"
                data-url-origin="{{ rut('ajax.selectStruct', 'subject') }}" disabled
                placeholder="{{ __('Subject Audit') }}">
            </select>
        </div>
        <div class="col-12 col-sm-6 col-xl-3 mr-n6 pb-2">
            <select name="auditee_id" data-post="auditee_id"
                class="form-control filter-control base-plugin--select2-ajax filterUnitKerja" id="filterUnitKerja"
                data-url="{{ rut('ajax.selectStruct', 'department_auditee') }}" placeholder="{{ __('Dept. Auditee') }}">
                <option value="">{{ __('Dept. Auditee') }}</option>
            </select>
        </div>
    </div>
@endsection

@section('buttons')
@endsection

@push('scripts')
    <script>
        $(function() {
            $('.content-page')
                .on('change', 'select.filter-control.filter-type-laporan', function(e) {
                    var me = $(this);
                    if (me.val()) {
                        BaseContent.replaceByUrl("{{ url('report/risk-assessment') }}/" + this.value);
                    }
                    BasePlugin.initSelect2();
                })
                .on('change', 'select.filter-type-id', function(e) {
                    var me = $(this);
                    var objectId = $('select.filter-object');

                    if (me.val()) {
                        var urlOrigin = objectId.data('url-origin');
                        var urlParam = $.param({
                            category: 'by_type',
                            type_id: me.val()
                        });
                        objectId.data('url', decodeURIComponent(decodeURIComponent(urlOrigin + '?' +
                            urlParam)));
                        objectId.val(null).prop('disabled', false);
                    } else {
                        objectId.prop('disabled', true).val(null); // Disable and reset value
                    }

                    BasePlugin.initSelect2();
                });
        });
    </script>
@endpush

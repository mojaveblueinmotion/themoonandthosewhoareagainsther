@extends('layouts.lists')
@section('filters')
    <div class="row">
        <div class="mr-n2 ml-4 pb-2" style="width: 80px">
            <input type="text" class="form-control filter-control base-plugin--datepicker-3" data-post="year"
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
            @php
                $is_auditor = $user->position?->imAuditor();
                $is_presdir = $user->position?->location->type == 1;
                $is_financedir = $user->position?->location->type == 2;
                $is_boc = $user->position?->location->level == 'boc';
                $is_bod = $user->position?->location->level == 'bod';
            @endphp
            <select name="auditee_id" data-post="auditee_id"
                class="form-control filter-control base-plugin--select2-ajax unitKerja" id="unitKerja"
                @if ($is_auditor || $is_financedir || $is_presdir || $is_boc) data-url="{{ rut('ajax.selectStruct', ['search' => 'department_auditee']) }}"
                @elseif ($is_bod)
                data-url="{{ rut('ajax.selectStruct', ['search' => 'department_auditee', 'parent_id' => $user->position->location_id ?? null]) }}"
                    @else
                    data-url="{{ rut('ajax.selectStruct', ['search' => 'department_auditee', 'id' => $user->position->location_id ?? null]) }}" @endif
                placeholder="{{ __('Dept. Auditee') }}">
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
                })
                .on('click', '.reset-filter .reset.button', function(e) {
                    var objectId = $('select.filter-object');
                    var urlOrigin = objectId.data('url-origin');
                    var urlParam = $.param({
                        category: ''
                    });
                    objectId.data('url', decodeURIComponent(decodeURIComponent(urlOrigin + '?' + urlParam)));
                    objectId.val(null).prop('disabled', false);
                    BasePlugin.initSelect2();
                    setTimeout(() => {
                        $('.filter-status').select2('destroy').val('*').change();
                        BasePlugin.initSelect2();
                    }, 200);
                });
        });
    </script>
@endpush

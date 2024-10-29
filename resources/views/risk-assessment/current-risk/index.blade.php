@extends('layouts.lists')

@section('filters')
    <div class="row">
        <div class="col-12 col-sm-3 col-xl-1 mr-n6 pb-2">
            <input class="form-control base-plugin--datepicker-3 filter-control" data-post="periode"
                placeholder="{{ __('Periode') }}">
        </div>
        <div class="col-12 col-sm-6 col-xl-3 mr-n6">
            <select data-post="type_id" class="form-control filter-control base-plugin--select2-ajax filter-type-id"
                data-url="{{ rut('ajax.selectTypeAudit', ['search' => 'without_investigasi']) }}"
                placeholder="{{ __('Jenis Audit') }}">
                <option value="">{{ __('Jenis Audit') }}</option>
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
            <select data-post="auditee_id" class="form-control filter-control base-plugin--select2-ajax unitKerja"
                id="unitKerja" data-url="{{ rut('ajax.selectStruct', 'department_auditee') }}"
                placeholder="{{ __('Dept. Auditee') }}">
                <option value="">{{ __('Dept. Auditee') }}</option>
            </select>
        </div>
        <div class="col-12 col-sm-5 col-md-2 pb-2">
            <select class="form-control filter-control base-plugin--select2 filter-status" data-post="status"
                data-placeholder="{{ __('Status') }}">
                <option value="*">Semua</option>
                <option value="new">New</option>
                <option value="draft">Draft</option>
                <option value="waiting.approval">Waiting Approval</option>
                <option value="waiting.approval.revisi">Waiting Approval Revisi</option>
                <option value="rejected">Rejected</option>
                <option value="completed">Completed</option>
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
            $('.content-page').on('change', 'select.filter-type-id', function(e) {
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
            }).on('click', '.filter.button, .reset.button', function() {
                setTimeout(() => {
                    $('.filter-status').select2('destroy').val('*').change();
                    BasePlugin.initSelect2();
                }, 200);
            });
        });
    </script>
@endpush

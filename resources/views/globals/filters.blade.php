@php
    $user = auth()->user();
@endphp
<div class="mr-n2 ml-4 pb-2" style="width: 80px">
    <input type="text" class="form-control filter-control base-plugin--datepicker-3" data-post="year"
        placeholder="{{ __('Tahun') }}">
</div>
<div class="col-12 col-sm-6 col-md-2 mr-n6 pb-2">
    <select name="type_id" data-post="type_id" class="form-control filter-control base-plugin--select2-ajax filter-type-id"
        data-url="{{ rut('ajax.selectTypeAudit', ['search' => 'all']) }}" placeholder="{{ __('Jenis Audit') }}">
        <option value="">{{ __('Jenis Audit') }}</option>
    </select>
</div>
<div class="col-12 col-sm-6 col-xl-3 mr-n6 pb-2">
    <select data-post="object_id" class="form-control filter-control base-plugin--select2-ajax filter-object" disabled
        data-url-origin="{{ rut('ajax.selectStruct', ['search' => 'subject']) }}"
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
        @if ($user->hasRole('Auditor') || $user->hasRole('Kepala Divisi Internal Audit')) data-url="{{ rut('ajax.selectStruct', ['search' => 'department_auditee']) }}"
                    @else
                    data-url="{{ rut('ajax.selectStruct', ['search' => 'department_auditee', 'id' => $user->position->location_id ?? null]) }}" @endif
        placeholder="{{ __('Dept. Auditee') }}">
        <option value="">{{ __('Dept. Auditee') }}</option>
    </select>
</div>
<div class="col-12 col-sm-5 col-md-2 mr-n6 pb-2">
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

@push('scripts')
    <script>
        $(function() {
            $('.content-page')
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

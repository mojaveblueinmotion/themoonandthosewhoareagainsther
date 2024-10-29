@extends('layouts.lists')

@section('filters')
<div class="row">
	<div class="ml-4 pb-2 mr-n2" style="width: 100px">
		<input type="text" data-post="year" class="form-control filter-control base-plugin--datepicker-3"
			placeholder="{{ __('Tahun') }}">
	</div>
	<div class="ml-4 mr-n2" style="width: 200px">
        <select name="type_id" data-post="type_id" class="form-control filter-control base-plugin--select2-ajax filter-type-id"
            data-url="{{ rut('ajax.selectTypeAudit', ['search' => 'all']) }}"
            placeholder="{{ __('Jenis Audit') }}">
            <option value="">{{ __('Jenis Audit') }}</option>
        </select>
    </div>
    <div class="ml-4 mr-n2" style="width: 250px">
        <select data-post="subject" id="subject_id2" class="form-control filter-control base-plugin--select2-ajax filter-object"
            data-url="{{ rut('ajax.selectStruct', ['search' => 'subject', 'type_id' => '']) }}"
            data-url-origin="{{ rut('ajax.selectStruct', 'subject') }}" disabled
            placeholder="{{ __('Subject Audit') }}">
        </select>
    </div>
    <div class="ml-4 mr-n2" style="width: 250px">
        <select name="auditee_id" data-post="auditee_id" class="form-control filter-control base-plugin--select2-ajax unitKerja2" id="unitKerja2"
            data-url="{{ rut('ajax.selectStruct', 'unit_kerja') }}" placeholder="{{ __('Dept. Auditee') }}">
            <option value="">{{ __('Dept. Auditee') }}</option>
        </select>
    </div>
</div>
@endsection

@section('buttons')
@if (auth()->user()->checkPerms($perms.'.create'))
@include('layouts.forms.btnAdd')
@endif
@endsection

@push('scripts')
<script>
	$(function () {
            $('.content-page').on('change', 'select.filter-control.filter-type-id', function(e) {
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


            $('.content-page').on('click', '.reset-filter .reset.button', function (e) {
                var objectId = $('select.filter-object');
                var urlOrigin = objectId.data('url-origin');
                var urlParam = $.param({category: ''});
                objectId.data('url', decodeURIComponent(decodeURIComponent(urlOrigin+'?'+urlParam)));
                objectId.val(null).prop('disabled', false);
                BasePlugin.initSelect2();
            });
        });
</script>
@endpush

@extends('layouts.lists')

@section('filters')
    <div class="row">
        <div class="ml-6 pb-2 mr-n2" style="width: 250px">
            <input type="text" data-post="procedure" class="form-control filter-control" placeholder="{{ __('Nama') }}">
        </div>
        <div class="col-12 col-sm-6 col-md-3 mr-n6 pb-2">
            <select name="type_id" data-post="type_id"
                class="form-control filter-control base-plugin--select2-ajax filter-type-id"
                data-url="{{ rut('ajax.selectTypeAudit', ['search' => 'all']) }}" placeholder="{{ __('Jenis Audit') }}">
                <option value="">{{ __('Jenis Audit') }}</option>
            </select>
        </div>
		<div class="col-12 col-sm-6 col-xl-3 mr-n6 pb-2">
			<select name="subject_id" data-post="subject"
				class="form-control filter-control base-plugin--select2-ajax subject_id" id="subject_id"
				data-url="{{ rut('ajax.selectStruct', ['search' => 'subject', 'type_id' => '']) }}"
                data-url-origin="{{ rut('ajax.selectStruct', 'subject') }}" disabled
				placeholder="{{ __('Subjek Audit') }}">
				<option value="">{{ __('Subjek Audit') }}</option>
			</select>
		</div>
        <div class="col-12 col-sm-6 col-md-3 mr-n6 pb-2">
            <select name="aspect_id" data-post="aspect_id"
                class="form-control filter-control base-plugin--select2-ajax filter-aspect-id"
				data-url="{{ rut('ajax.selectAspect', ['search' => 'by_subject', 'type_id' => '', 'subject_id' => '']) }}"
                data-url-origin="{{ rut('ajax.selectAspect', 'by_subject') }}" disabled
				placeholder="{{ __('Lingkup Audit') }}">
                <option value="">{{ __('Lingkup Audit') }}</option>
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
        $(function() {
            window.formSuccessCallback = function(resp, form, options) {
                $('#nameCtrl').val('');
                $('#descCtrl').val('');
            };
            $('.content-page')
            .on('change', 'select.filter-type-id', function(e) {
				var me = $(this);
				if (me.val()) {
					var subjectId = $('select.subject_id')
					var unitKerjaId = $('select.unitKerja');
					var urlOrigin = subjectId.data('url-origin');
					var urlParam = $.param({
						search: 'subject',
						type_id: me.val(),
					});
					subjectId.data('url', decodeURIComponent(decodeURIComponent(urlOrigin + '?' +
						urlParam)));
					subjectId.val(null).prop('disabled', false);
					unitKerjaId.val(null).prop('disabled', true);
				}
				BasePlugin.initSelect2();
			})
			.on('change', 'select.subject_id', function(e) {
				var me = $(this);
				if (me.val()) {
					var aspect = $('select.filter-aspect-id')
					var subject_id = $('select.subject_id')
					var type_id = $('select.filter-type-id')
					var urlOrigin = aspect.data('url-origin');
					var urlParam = $.param({
						search: 'by_subject',
						type_id: type_id.val(),
						subject_id: subject_id.val(),
					});
					aspect.data('url', decodeURIComponent(decodeURIComponent(urlOrigin + '?' +
						urlParam)));
					aspect.val(null).prop('disabled', false);
				}
				BasePlugin.initSelect2();
			})
			.on('change', 'select.filter-object', function(e) {
				var me = $(this);
				if (me.val()) {
					var aspect = $('select.filter-aspect-id')
					var subject_id = $('select.filter-object')
					var type_id = $('select.filter-type-id')
					var urlOrigin = aspect.data('url-origin');
					var urlParam = $.param({
						search: 'by_aspect',
						type_id: type_id.val(),
						subject_id: subject_id.val(),
					});
					aspect.data('url', decodeURIComponent(decodeURIComponent(urlOrigin + '?' +
						urlParam)));
					aspect.val(null).prop('disabled', false);
				}
				BasePlugin.initSelect2();
			});


            $('.content-page').on('click', '.reset-filter .reset.button', function(e) {
                var me = $(this);
                var objectId = $('select.filter-object');
                var urlOrigin = objectId.data('url-origin');
                var urlParam = $.param({
                    category: ''
                });
                objectId.data('url', decodeURIComponent(decodeURIComponent(urlOrigin + '?' + urlParam)));
                objectId.val(null).prop('disabled', true);
                BasePlugin.initSelect2();
            });
        });
    </script>
@endpush

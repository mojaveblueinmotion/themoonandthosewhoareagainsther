@extends('layouts.lists')

@section('filters')
<div class="row">
	<div class="ml-6 pb-2 mr-n2" style="width: 100px">
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
	<div class="ml-4" style="width: 250px">
		<select data-post="object" class="form-control filter-control base-plugin--select2-ajax filter-object"
			data-url="{{ rut('ajax.selectObject', ['category' => 'operation']) }}"
			data-url-origin="{{ rut('ajax.selectObject') }}" placeholder="{{ __('Subject Audit') }}" disabled>
			<option value="">{{ __('Subject Audit') }}</option>
		</select>
	</div>
</div>
@endsection

@section('buttons')
@if (auth()->user()->checkPerms($perms.'.create'))
@include('layouts.forms.btnAdd', ['baseContentReplace' => true])
@endif
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
                    objectId.data('url', decodeURIComponent(decodeURIComponent(urlOrigin + '?' + urlParam)));
                    objectId.val(null).prop('disabled', false);
                } else {
                    objectId.prop('disabled', true).val(null); // Disable and reset value
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

@extends('layouts.lists')

@section('filters')
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3 mr-n6 pb-2">
            <select disabled name="perusahaan_id" data-post="perusahaan_id" class="form-control filter-control base-plugin--select2-ajax perusahaan_id"
                data-url="{{ rut('ajax.selectLapak', ['search' => 'all']) }}"
                data-url-origin="{{ rut('ajax.selectLapak', ['search' => 'all']) }}"
                placeholder="{{ __('Lapak ') }}">
                <option value="1" selected>{{ App\Models\Master\Pembukuan\Lapak::where('id',1)->first()->name }}</option>
            </select>
        </div>
        <div class="ml-6 pb-2" style="width: 100px">
			<input type="text" class="form-control filter-control base-plugin--datepicker-2"
				data-post="month"
				placeholder="{{ __('Bulan') }}">
		</div>
    </div>
@endsection

@section('buttons')
    @if (auth()->user()->checkPerms($perms . '.create'))
        @include('layouts.forms.btnAdd')
    @endif
@endsection

@push('scripts')
    <script>
        $(function() {
            window.formSuccessCallback = function(resp, form, options) {
                $('#nameCtrl').val('');
            };
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
            })
            .on('change', 'select.filter-object', function(e) {
                var me = $(this);
                if (me.val()) {
                    var mainProcess = $('select.main_process_id')
                    var subject_id = $('select.filter-object')
                    var urlOrigin = mainProcess.data('url-origin');
                    var urlParam = $.param({
                        subject_id: subject_id.val(),
                    });
                    mainProcess.data('url', decodeURIComponent(decodeURIComponent(urlOrigin + '?' +
                        urlParam)));
                    mainProcess.val(null).prop('disabled', false);
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

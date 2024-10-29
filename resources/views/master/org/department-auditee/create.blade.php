@extends('layouts.modal')

@section('action', rut($routes . '.store'))

@section('modal-body')
    @method('POST')
	<div class="form-group row">
		<label class="col-sm-3 col-form-label">{{ __('Tahun') }}</label>
		<div class="col-sm-9 parent-group text-right">
			<input type="text" name="year" class="form-control base-plugin--datepicker-3 width-100px ml-auto text-right" placeholder="{{ __('Tahun') }}">
		</div>
	</div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">{{ __('Jenis Audit') }}</label>
        <div class="col-sm-9 parent-group">
            <select name="type_id" class="form-control filter-control base-plugin--select2-ajax type_id"
                data-url="{{ rut('ajax.selectTypeAudit', ['search' => 'all']) }}"
                data-url-origin="{{ rut('ajax.selectTypeAudit', ['search' => 'all']) }}"
                placeholder="{{ __('Pilih Salah Satu ') }}">
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">{{ __('Subject Audit') }}</label>
        <div class="col-sm-9 parent-group">
            <select name="subject_id" id="subjectAudit" class="form-control base-plugin--select2-ajax subjectAudit"
                data-url="{{ rut('ajax.selectStruct', ['search' => 'subject', 'type_id' => '']) }}"
                data-url-origin="{{ rut('ajax.selectStruct', 'subject') }}" disabled
                placeholder="{{ __('Pilih Salah Satu') }}">
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 col-form-label">{{ __('Department Auditee') }}</label>
        <div class="col-md-9 parent-group">
            <select name="departments[]" class="form-control base-plugin--select2-ajax"
                data-url="{{ route('ajax.selectStruct', [
                    'search' => 'department_auditee',
                ]) }}"
                multiple placeholder="{{ __('Pilih Beberapa') }}">
                <option value="">{{ __('Pilih Beberapa') }}</option>
            </select>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        $(function() {
            $('.modal-dialog-right-bottom').removeClass('modal-md').addClass('modal-lg');

            $('.content-page').on('change', 'select.type_id', function(e) {
                    var me = $(this);
                    if (me.val()) {
                        var subjectId = $('select.subjectAudit')
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
                        var aspectId = $('select.aspect_id')
                        var subject_id = $('select.subject_id')
                        var urlOrigin = aspectId.data('url-origin');
                        var urlParam = $.param({
                            search: 'parent_level',
                            parent_id: subject_id.val(),
                            parent_id: subject_id.val(),
                        });
                        aspectId.data('url', decodeURIComponent(decodeURIComponent(urlOrigin + '?' +
                            urlParam)));
                        aspectId.val(null).prop('disabled', false);
                    }
                    BasePlugin.initSelect2();
                })
                .on('change', 'select.subjectAudit', function(e) {
                    var me = $(this);
                    if (me.val()) {
                        var aspect = $('select.aspect_id')
                        var subject_id = $('select.subjectAudit')
                        var type_id = $('select.type_id')
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
                .on('change', '#subject_id', function() {
                    $.ajax({
                        method: 'GET',
                        url: '{{ yurl('/ajax/unit-kerja') }}',
                        data: {
                            group_id: $(this).val()
                        },
                        success: function(response, state, xhr) {
                            let options = ``;
                            for (let item of response) {
                                options +=
                                    `<option selected value='${item.id}'>${item.struct.name}</option>`;
                            }
                            $('#unitKerja').select2('destroy');
                            $('#unitKerja').html(options);
                            $('#unitKerja').select2();
                            console.log(54, response, options);
                        },
                        error: function(a, b, c) {
                            console.log(a, b, c);
                        }
                    });
                });

            $('.content-page').on('change', 'select.object_id', function(e) {
                var me = $(this);
                if (me.val()) {
                    var ctrlAuditor = $('select.ctrlAuditor');
                    var typeId = $('select.type_id');
                    var urlOrigin = ctrlAuditor.data('url-origin');
                    var urlParam = $.param({
                        search: '',

                    });
                    // ctrlAuditor.data('url', decodeURIComponent(decodeURIComponent(urlOrigin + '?' +
                    //     urlParam)));
                    ctrlAuditor.val(null).prop('disabled', false);

                }
                BasePlugin.initSelect2();
            });

            $('.content-page').on('change', 'select.unitKerja', function(e) {
                var me = $(this);
                if (me.val()) {
                    var ctrlAuditor = $('select.ctrlAuditor');
                    var typeId = $('select.object_id');
                    var urlOrigin = ctrlAuditor.data('url-origin');
                    var urlParam = $.param({
                        search: '',

                    });
                    // ctrlAuditor.data('url', decodeURIComponent(decodeURIComponent(urlOrigin + '?' +
                    //     urlParam)));
                    ctrlAuditor.val(null).prop('disabled', false);

                }
                BasePlugin.initSelect2();
            });


        });
    </script>
@endpush

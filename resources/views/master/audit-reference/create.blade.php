@extends('layouts.modal')

@section('action', rut($routes . '.store'))

@section('modal-body')
    @method('POST')
    <div class="form-group row">
        <label class="col-md-3 col-form-label">{{ __('Jenis Audit') }}</label>
        <div class="col-md-9 parent-group">
            <select name="type_id" data-post="type_id" class="form-control filter-control base-plugin--select2-ajax type_id"
                data-url="{{ rut('ajax.selectTypeAudit', ['search' => 'all']) }}"
                data-url-origin="{{ rut('ajax.selectTypeAudit', ['search' => 'all']) }}"
                placeholder="{{ __('Pilih Salah Satu ') }}">
                <option value="">{{ __('Jenis ') }}</option>
            </select>
        </div>
    </div>

    <div class="form-group row" id="opspec_object">
        <label class="col-sm-3 col-form-label">{{ __('Subject Audit') }}</label>
        <div class="col-sm-9 parent-group">
            <select name="subject_id" class="form-control base-plugin--select2-ajax subjectAudit"
                data-url="{{ rut('ajax.selectStruct', ['search' => 'subject', 'type_id' => '']) }}"
                data-url-origin="{{ rut('ajax.selectStruct', 'subject') }}" disabled id="subject_id"
                placeholder="{{ __('Pilih Salah Satu') }}">
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">{{ __('Main Process') }}</label>
        <div class="col-sm-9 parent-group">
            <select name="main_process_id" id="mainProcessCtrl" class="form-control base-plugin--select2-ajax mainProcessCtrl"
                data-url="{{ rut('ajax.selectMainProcess') }}"
                data-url-origin="{{ rut('ajax.selectMainProcess') }}" disabled
                placeholder="{{ __('Pilih Salah Satu') }}">
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">{{ __('Lingkup Audit') }}</label>
        <div class="col-9 parent-group">
            <select name="aspect_id"class="form-control base-plugin--select2-ajax aspect_id"
                data-url="{{ rut('ajax.selectAspect', ['search' => 'by_subject', 'type_id' => '', 'subject_id' => '']) }}"
                data-url-origin="{{ rut('ajax.selectAspect', 'by_subject') }}" disabled
                placeholder="{{ __('Pilih Salah Satu') }}">
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 col-form-label">{{ __('Tujuan Audit') }}</label>
        <div class="col-md-9 parent-group">
            <select name="objective_id" class="form-control base-plugin--select2-ajax objective_id"
                data-url="{{ rut('ajax.selectObjective', ['search' => 'by_aspect', 'aspect_id' => '']) }}"
                data-url-origin="{{ rut('ajax.selectObjective', 'by_aspect') }}" disabled
                placeholder="{{ __('Pilih Salah Satu') }}">
                <option value="">{{ __('Pilih Salah Satu') }}</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 col-form-label">{{ __('Langkah Kerja') }}</label>
        <div class="col-md-9 parent-group">
            <select name="procedure_id" class="form-control base-plugin--select2-ajax procedure_id"
                data-url="{{ rut('ajax.selectProcedure', ['objective_id' => '']) }}"
                data-url-origin="{{ rut('ajax.selectProcedure') }}" disabled
                placeholder="{{ __('Pilih Salah Satu') }}">
                <option value="">{{ __('Pilih Salah Satu') }}</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 col-form-label">{{ __('Nama') }}</label>
        <div class="col-md-9 parent-group">
            <input type="text" name="name" class="form-control" id="nameCtrl" maxlength="255"
                placeholder="{{ __('Nama') }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 col-form-label">{{ __('Deskripsi') }}</label>
        <div class="col-md-9 parent-group">
            <textarea name="description" class="form-control" id="descCtrl" placeholder="{{ __('Deskripsi') }}"></textarea>
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
                        var aspectId = $('select.aspect_id')
                        var urlOrigin = subjectId.data('url-origin');
                        var urlParam = $.param({
                            search: 'subject',
                            type_id: me.val(),
                        });
                        subjectId.data('url', decodeURIComponent(decodeURIComponent(urlOrigin + '?' +
                            urlParam)));
                        subjectId.val(null).prop('disabled', false);
                        aspectId.val(null);
                    }
                    BasePlugin.initSelect2();
                })
                .on('change', 'select.aspect_id', function(e) {
                    var me = $(this);
                    if (me.val()) {
                        var aspectId = $('select.aspect_id')
                        var objective_id = $('select.objective_id')
                        var urlOrigin = objective_id.data('url-origin');
                        var urlParam = $.param({
                            aspect_id: aspectId.val(),
                        });
                        objective_id.data('url', decodeURIComponent(decodeURIComponent(urlOrigin + '?' +
                            urlParam)));
                        objective_id.val(null).prop('disabled', false);
                    }
                    BasePlugin.initSelect2();
                })
                .on('change', 'select.objective_id', function(e) {
                    var me = $(this);
                    if (me.val()) {
                        var objective_id = $('select.objective_id')
                        var procedure_id = $('select.procedure_id')
                        var urlOrigin = procedure_id.data('url-origin');
                        var urlParam = $.param({
                            objective_id: objective_id.val(),
                        });
                        procedure_id.data('url', decodeURIComponent(decodeURIComponent(urlOrigin + '?' +
                            urlParam)));
                        procedure_id.val(null).prop('disabled', false);
                    }
                    BasePlugin.initSelect2();
                })
                .on('change', 'select.subjectAudit', function(e) {
                    var me = $(this);
                    if (me.val()) {
                        var mainProcess = $('select.mainProcessCtrl');
                        var subject_id = $('select.subjectAudit');
                        var type_id = $('select.type_id');
                        var urlOriginMain = mainProcess.data('url-origin');
                        var urlParamMain = $.param({
                            subject_id: subject_id.val(),
                        });
                        mainProcess.data('url', decodeURIComponent(decodeURIComponent(urlOriginMain + '?' +
                            urlParamMain)));
                        mainProcess.val(null).prop('disabled', false);


                        var aspect = $('select.aspect_id')
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
                .on('change', 'select.mainProcessCtrl', function(e) {
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
                            main_process_id: me.val(),
                        });
                        aspect.data('url', decodeURIComponent(decodeURIComponent(urlOrigin + '?' +
                            urlParam)));
                        aspect.val(null).prop('disabled', false);
                    }
                    BasePlugin.initSelect2();
                });
        });
    </script>
@endpush

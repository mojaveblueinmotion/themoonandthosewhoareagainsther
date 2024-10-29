@extends('layouts.modal')

@section('action', route($routes . '.store'))

@section('modal-body')
    @method('POST')
    <input type="hidden" id="departmentAuditeeCtrl" name="department_auditee_id">
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Tahun') }}</label>
        <div class="col-sm-8 parent-group">
            <input name="year" class="form-control base-plugin--datepicker-3" data-orientation="bottom" id="tahun"
                placeholder="{{ __('Tahun') }}" data-options='@json(['endDate' => "$startYear"])'>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Jenis Audit') }}</label>
        <div class="col-sm-8 parent-group">
            <select name="type_id" data-post="type_id" class="form-control filter-control base-plugin--select2-ajax type_id"
                data-url="{{ rut('ajax.selectTypeAudit', ['search' => 'all']) }}"
                data-url-origin="{{ rut('ajax.selectTypeAudit', ['search' => 'all']) }}"
                placeholder="{{ __('Pilih Salah Satu ') }}">
                <option value="">{{ __('Jenis ') }}</option>
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Subject Audit') }}</label>
        <div class="col-sm-8 parent-group">
            <select name="object_id" id="subject_id" class="form-control base-plugin--select2-ajax subject_id"
                data-url="{{ rut('ajax.selectStruct', ['search' => 'subject', 'type_id' => '']) }}"
                data-url-origin="{{ rut('ajax.selectStruct', 'subject') }}" disabled
                placeholder="{{ __('Pilih Salah Satu') }}">
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Dept Auditee') }}</label>
        <div class="col-sm-8 parent-group">
            <select name="unitKerja" disabled class="form-control base-plugin--select2-ajax unitKerja" id="unitKerja"
                data-url="{{ rut('ajax.selectStruct', 'subject') }}" multiple>
            </select>
        </div>
    </div>

    <div class="form-group row" hidden>
        <label class="col-sm-4 col-form-label">{{ __('Dept Auditee') }}</label>
        <div class="col-sm-8 parent-group">
            <select name="unitKerja" class="form-control base-plugin--select2-ajax unitKerja" id="unitKerja2"
                data-url="{{ rut('ajax.selectStruct', 'subject') }}" multiple>
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('No LHA') }}</label>
        <div class="col-sm-8 parent-group">
            <input name="code" class="form-control" placeholder="{{ __('No LHA') }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Tanggal LHA') }}</label>
        <div class="col-sm-8 parent-group">
            <input name="date" class="form-control base-plugin--datepicker" data-options='@json([
                'startDate' => '',
                'endDate' => now()->format('d/m/Y'),
            ])'
                placeholder="{{ __('Tanggal LHA') }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Lampiran') }}</label>
        <div class="col-sm-8 parent-group">
            <div class="custom-file">
                <input type="hidden" name="attachments[uploaded]" class="uploaded" value="0">
                <input type="file" multiple class="custom-file-input base-form--save-temp-files" data-name="attachments"
                    data-container="parent-group" data-max-size="20024" data-max-file="100" accept="*">
                <label class="custom-file-label" for="file">Choose File</label>
            </div>
            <div class="form-text text-muted">*Maksimal 20MB</div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('.modal-dialog-right-bottom').removeClass('modal-md').addClass('modal-lg');
            $('.content-page')
                .on('change', 'select.type_id', function(e) {
                    var me = $(this);
                    if (me.val()) {
                        var departmentAuditeeId = $('select.subject_id');
                        var urlOrigin = departmentAuditeeId.data('url-origin');
                        var urlParam = $.param({
                            search: 'subject',
                            year: $('#tahun').val(),
                            type_id: me.val(),
                        });
                        departmentAuditeeId.data('url', decodeURIComponent(decodeURIComponent(urlOrigin + '?' +
                            urlParam)));
                        departmentAuditeeId.val(null).prop('disabled', false);
                    }
                    BasePlugin.initSelect2();
                })
                .on('change', 'select.subject_id', function(e) {
                    var departmentAuditeeId = $('select.subject_id');
                    var periode = $('input#tahun');
                    console.log(departmentAuditeeId.val());
                    console.log(periode.val());
                    if (departmentAuditeeId !== null && periode !== null) {
                        $.ajax({
                            method: 'GET',
                            url: '{{ yurl('/ajax/unit-kerja') }}',
                            data: {
                                subject_id: departmentAuditeeId.val(),
                                year: periode.val(),
                            },
                            success: function(response, state, xhr) {
                                console.log(91, response);
                                try {
                                    if (response[0] && response[0].departments) {
                                        $('#departmentAuditeeCtrl').val(response[0].id);
                                        let options = ``;
                                        for (let item of response[0].departments) {
                                            options +=
                                                `<option selected value='${item.id}'>${item.name}</option>`;
                                        }
                                        $('.unitKerja').select2('destroy');
                                        $('.unitKerja').html(options);
                                        $('.unitKerja').select2();
                                        $('.unitKerja2').select2('destroy');
                                        $('.unitKerja2').html(options);
                                        $('.unitKerja2').select2();
                                    } else {
                                        let options = ``;
                                        $('#unitKerja').select2('destroy');
                                        $('#unitKerja').html(options);
                                        $('#unitKerja').select2();
                                        $('#unitKerja2').select2('destroy');
                                        $('#unitKerja2').html(options);
                                        $('#unitKerja2').select2();
                                        // console.error('Departments data is not available');
                                        // $('#result').text('Departments data is not available');
                                    }
                                } catch (error) {}
                            },
                            error: function(a, b, c) {
                                console.log(a, b, c);
                            }
                        });
                    }
                })
                .on('change', 'input#tahun', function(e) {
                    var departmentAuditeeId = $('select.subject_id');
                    var periode = $('input#tahun');
                    if (departmentAuditeeId !== null && periode !== null) {
                        $.ajax({
                            method: 'GET',
                            url: '{{ yurl('/ajax/unit-kerja') }}',
                            data: {
                                subject_id: departmentAuditeeId.val(),
                                year: periode.val(),
                            },
                            success: function(response, state, xhr) {
                                console.log(136, response);
                                try {
                                    if (response[0] && response[0].departments) {
                                        $('#departmentAuditeeCtrl').val(response[0].id);
                                        let options = ``;
                                        for (let item of response[0].departments) {
                                            options +=
                                                `<option selected value='${item.id}'>${item.name}</option>`;
                                        }
                                        $('#unitKerja').select2('destroy');
                                        $('#unitKerja').html(options);
                                        $('#unitKerja').select2();
                                        $('#unitKerja2').select2('destroy');
                                        $('#unitKerja2').html(options);
                                        $('#unitKerja2').select2();
                                    } else {
                                        let options = ``;
                                        $('#unitKerja').select2('destroy');
                                        $('#unitKerja').html(options);
                                        $('#unitKerja').select2();
                                        $('#unitKerja2').select2('destroy');
                                        $('#unitKerja2').html(options);
                                        $('#unitKerja2').select2();
                                        // console.error('Departments data is not available');
                                        // $('#result').text('Departments data is not available');
                                    }
                                } catch (error) {}
                            },
                            error: function(a, b, c) {
                                console.log(a, b, c);
                            }
                        });
                    }
                });
        });
    </script>
@endpush

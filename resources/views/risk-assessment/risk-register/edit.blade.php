@extends('layouts.modal')

@section('action', route($routes . '.update', $record->id))

{{-- @section('action', route($routes . '.store')) --}}
@php
    $options = [
        'format' => 'mm/yyyy',
    ];
@endphp

@section('modal-body')
    @method('PATCH')

    <div class="form-group row">
        <label class="col-md-3 col-form-label">{{ __('Periode') }}</label>
        <div class="col-md-9 parent-group">
            <input type="hidden" name="periode" value="{{ $record->periode->format('Y') }}" id="tahun">
            <input type="text" disabled name="periode" class="form-control base-plugin--datepicker-2 periode"
                data-options='@json($options)' placeholder="{{ __('Periode') }}"
                value="{{ $record->periode->format('Y') }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 col-form-label">{{ __('Jenis Audit') }}</label>
        <div class="col-md-9 parent-group">
            <input type="hidden" name="type_id" value="{{ $record->type_id }}">
            <select class="form-control base-plugin--select2-ajax type_id"
                data-url="{{ rut('ajax.selectTypeAudit', ['search' => 'without_investigasi']) }}" disabled
                placeholder="{{ __('Pilih Salah Satu') }}">
                <option value="">{{ __('Pilih Salah Satu') }}</option>
                @if ($record->type_id)
                    <option value="{{ $record->type_id }}" selected>{{ $record->type->name }}</option>
                @endif
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">{{ __('Subject Audit') }}</label>
        <div class="col-sm-9 parent-group">
            <select name="object_id" id="subject_id" class="form-control base-plugin--select2-ajax subject_id"
                data-url="{{ rut('ajax.selectStruct', ['search' => 'subject', 'type_id' => '']) }}"
                data-url-origin="{{ rut('ajax.selectStruct', 'subject') }}" placeholder="{{ __('Pilih Salah Satu') }}">
                @if (!empty($record->subject))
                    <option value="{{ $record->subject->id }}" selected>{{ $record->subject->name }}</option>
                @endif
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">{{ __('Dept Auditee') }}</label>
        <div class="col-sm-9 parent-group">
            <select name="unitKerja" disabled class="form-control base-plugin--select2-ajax unitKerja" id="unitKerja"
                data-url="{{ rut('ajax.selectStruct', 'subject') }}" multiple>
                @foreach ($record->departmentAuditee->departments as $val)
                    <option selected>{{ $val->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group row" hidden>
        <label class="col-sm-3 col-form-label">{{ __('Dept Auditee') }}</label>
        <div class="col-sm-9 parent-group">
            <select name="unitKerja" class="form-control base-plugin--select2-ajax unitKerja" id="unitKerja2"
                data-url="{{ rut('ajax.selectStruct', 'subject') }}" multiple>
                @foreach ($record->departmentAuditee->departments as $val)
                    <option selected>{{ $val->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {

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
                                try {

                                    if (response[0] && response[0].departments) {
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
                                try {
                                    if (response[0] && response[0].departments) {
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

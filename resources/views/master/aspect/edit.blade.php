@extends('layouts.modal')

@section('action', rut($routes . '.update', $record->id))

@section('modal-body')
    @method('PATCH')
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Jenis Audit') }}</label>
        <div class="col-sm-8 parent-group">
            <input type="hidden" name="type_id" value="{{ $record->subject->type_id }}">
            <select class="form-control filter-control base-plugin--select2-ajax type_id"
                data-url="{{ rut('ajax.selectTypeAudit', ['search' => 'all']) }}"
                data-url-origin="{{ rut('ajax.selectTypeAudit', ['search' => 'all']) }}"
                placeholder="{{ __('Pilih Salah Satu ') }}" disabled>
                @if ($record->subject)
                    <option selected>{{ $record->subject->typeAudit->name }}</option>
                @endif
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Subject Audit') }}</label>
        <div class="col-sm-8 parent-group">
            <input type="text" name="object_id" value="{{ $record->subject->id }}" hidden>
            <select id="subject_id" class="form-control base-plugin--select2-ajax subject_id"
                data-url="{{ rut('ajax.selectStruct', 'subject') }}" data-placeholder="{{ __('Pilih Salah Satu') }}"
                disabled>
                @isset($record->subject)
                    <option value="{{ $record->subject->id }}">{{ $record->subject->name }}</option>
                @endisset
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Main Process') }}</label>
        <div class="col-sm-8 parent-group">
            <input type="text" name="main_process_id" value="{{ $record->main_process_id }}" hidden>
            <select name="main_process_id" id="main_process_id" class="form-control base-plugin--select2-ajax main_process_id"
                data-url="{{ rut('ajax.selectMainProcess', ['subject_id' => '']) }}"
                data-url-origin="{{ rut('ajax.selectMainProcess') }}" disabled
                placeholder="{{ __('Pilih Salah Satu') }}">
                @isset($record->mainProcess)
                    <option value="{{ $record->mainProcess->id }}">{{ $record->mainProcess->name }}</option>
                @endisset
            </select>
        </div>
    </div>

    {{-- <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Dept Auditee') }}</label>
        <div class="col-sm-8 parent-group">
            <select class="form-control base-plugin--select2-ajax unitKerja" id="unitKerja" disabled multiple>
                @foreach ($record->subject->childOfGroup as $item)
                    <option selected>{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
    </div> --}}
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Nama') }}</label>
        <div class="col-sm-8 parent-group">
            <input name="name" class="form-control" id="nameCtrl" maxlength="255" placeholder="{{ __('Nama') }}"
                value="{{ $record->name }}">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Deskripsi') }}</label>
        <div class="col-sm-8 parent-group">
            <textarea name="description" class="form-control" placeholder="{{ __('Deskripsi') }}">{{ $record->description }}</textarea>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('.content-page')

                .on('change', 'select.subject_id', function(e) {
                    var me = $(this);
                    if (me.val()) {
                        var objectId = $('select.object_id');
                        var urlOrigin = objectId.data('url-origin');
                        var urlParam = $.param({
                            parent_id: me.val()
                        });
                        objectId.data('url', decodeURIComponent(decodeURIComponent(urlOrigin + '?' +
                            urlParam)));
                        objectId.val(null).prop('disabled', false);
                    }
                    BasePlugin.initSelect2();
                })
                .on('change', 'select.subject_id', function(e) {
                    var me = $(this);
                    if (me.val()) {
                        var mainProcess = $('select.main_process_id')
                        var subject_id = $('select.subject_id')
                        var urlOrigin = mainProcess.data('url-origin');
                        var urlParam = $.param({
                            subject_id: subject_id.val(),
                        });
                        mainProcess.data('url', decodeURIComponent(decodeURIComponent(urlOrigin + '?' +
                            urlParam)));
                        mainProcess.val(null).prop('disabled', false);
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

@extends('layouts.modal')

@section('action', rut($routes . '.update', $record->id))

@section('modal-body')
    @method('PATCH')
    <div class="form-group row">
        <label class="col-md-3 col-form-label">{{ __('Jenis Audit') }}</label>
        <div class="col-md-9 parent-group">
            <select name="type_id" data-post="type_id" class="form-control filter-control base-plugin--select2-ajax type_id"
                data-url="{{ rut('ajax.selectTypeAudit', ['search' => 'all']) }}" placeholder="{{ __('Pilih Salah Satu ') }}"
                disabled>
                <option value="">{{ __('Jenis Audit') }}</option>
                @if ($record->aspect->subject)
                    <option selected>{{ $record->procedure->aspect->subject->typeAudit->name }}</option>
                @endif
            </select>
        </div>
    </div>
    <div class="form-group row" id="opspec_object">
        <label class="col-sm-3 col-form-label">{{ __('Subject Audit') }}</label>
        <div class="col-sm-9 parent-group">
            <input name="subject_id" type="text" class="form-control" value="{{ $record->procedure->aspect->subject->name }}"
                readonly>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">{{ __('Main Process') }}</label>
        <div class="col-sm-9 parent-group">
            <select name="main_process_id" id="mainProcessCtrl" class="form-control base-plugin--select2-ajax mainProcessCtrl"
                data-url="{{ rut('ajax.selectMainProcess') }}"
                data-url-origin="{{ rut('ajax.selectMainProcess') }}" disabled
                placeholder="{{ __('Pilih Salah Satu') }}">
                <option select value="{{ $record->procedure->aspect->mainProcess->id }}">{{ $record->procedure->aspect->mainProcess->name }}</option>
            </select>
        </div>
    </div>
    <div class="form-group row" id="aspect_griya">
        <label class="col-md-3 col-form-label">{{ __('Lingkup Audit') }}</label>
        <div class="col-md-9 parent-group">
            <select name="aspect_id" class="form-control base-plugin--select2-ajax aspect_object_id"
                data-url="{{ rut('ajax.selectAspect', [
                    'search' => 'parent_level',
                    'type_id' => $record->aspect->type_id ?? '',
                    'object_type' => $record->object_type ?? '',
                ]) }}"
                data-url-origin="{{ rut('ajax.selectAspect', ['search' => 'parent_level']) }}"
                placeholder="{{ __('Pilih Salah Satu') }}" disabled>
                <option value="">{{ __('Pilih Salah Satu') }}</option>
                @if ($aspect = $record->procedure->aspect)
                    <option value="{{ $aspect->id }}" selected>{{ $aspect->name }}</option>
                @endif
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 col-form-label">{{ __('Tujuan Audit') }}</label>
        <div class="col-md-9 parent-group">
            <select name="objective_id" class="form-control base-plugin--select2-ajax objective_id"
                data-url="{{ rut('ajax.selectObjective', ['search' => 'by_aspect', 'aspect_id' => $record->aspect_id]) }}"
                data-url-origin="{{ rut('ajax.selectObjective', 'by_aspect') }}" disabled
                placeholder="{{ __('Pilih Salah Satu') }}">
                <option value="">{{ __('Pilih Salah Satu') }}</option>
                @if ($objective = $record->procedure->objective)
                    <option value="{{ $objective->id }}" selected>{{ $objective->name }}</option>
                @endif
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
                @if ($procedure = $record->procedure)
                    <option value="{{ $procedure->id }}" selected>
                        {{ $procedure->number.'. '.$procedure->procedure }}
                    </option>
                @endif
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 col-form-label">{{ __('Nama') }}</label>
        <div class="col-md-9 parent-group">
            <input type="text" name="name" class="form-control" maxlength="255" placeholder="{{ __('Nama') }}"
                value="{{ $record->name }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 col-form-label">{{ __('Deskripsi') }}</label>
        <div class="col-md-9 parent-group">
            <textarea name="description" class="form-control" placeholder="{{ __('Deskripsi') }}">{!! $record->description !!}</textarea>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('.modal-dialog-right-bottom').removeClass('modal-md').addClass('modal-lg');

            $('.content-page').on('change', 'select.subject_id', function(e) {
                var me = $(this);
                if (me.val()) {
                    var subjectId = $('select.aspect_id');
                    var urlOrigin = subjectId.data('url-origin');
                    var urlParam = $.param({
                        parent_id: me.val()
                    });
                    subjectId.data('url', decodeURIComponent(decodeURIComponent(urlOrigin + '?' +
                        urlParam)));
                    subjectId.val(null).prop('disabled', false);

                }
                BasePlugin.initSelect2();
            });

            $('.content-page').on('change', 'select.aspect_id', function(e) {
                var me = $(this);
                if (me.val()) {
                    var ctrlAuditor = $('select.ctrlAuditor');
                    var typeId = $('select.subject_id');
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

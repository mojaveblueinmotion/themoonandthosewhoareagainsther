@extends('layouts.modal')

@section('modal-body')
    <div class="form-group row">
        <label class="col-md-3 col-form-label">{{ __('Jenis Audit') }}</label>
        <div class="col-md-9 parent-group">
            <input type="text" value="{{ $record->aspect->subject->typeAudit->name ?? '' }}" class="form-control" disabled>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 col-form-label">{{ __('Subject Audit') }}</label>
        <div class="col-md-9 parent-group">
            <input type="text" value="{{ $record->aspect->subject->name ?? '' }}" class="form-control" disabled>
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
    <div class="form-group row">
        <label class="col-md-3 col-form-label">{{ __('Lingkup Audit') }}</label>
        <div class="col-md-9 parent-group">
            <input type="text" value="{{ $record->aspect->name }}" class="form-control" disabled>
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
            <input type="text" value="{{ $record->name }}" class="form-control" disabled>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 col-form-label">{{ __('Deskripsi') }}</label>
        <div class="col-md-9 parent-group">
            <textarea name="description" class="form-control" disabled>{!! $record->description !!}</textarea>
        </div>
    </div>
@endsection

@section('buttons')
@endsection

@push('scripts')
    <script>
        $(function() {
            $('.modal-dialog-right-bottom').removeClass('modal-md').addClass('modal-lg');
        });
    </script>
@endpush

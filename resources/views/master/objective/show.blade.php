@extends('layouts.modal')

@section('modal-body')
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">{{ __('Jenis Audit') }}</label>
        <div class="col-sm-9 parent-group">
            <input type="text" name="type_id" value="{{ $record->aspect->subject->typeAudit->id }}" hidden>
            <select disabled name="type_id" class="form-control filter-control base-plugin--select2-ajax type_id"
                data-url="{{ rut('ajax.selectTypeAudit', ['search' => 'all']) }}" disabled
                data-url-origin="{{ rut('ajax.selectTypeAudit', ['search' => 'all']) }}"
                placeholder="{{ __('Pilih Salah Satu ') }}">
                <option select value="{{ $record->aspect->subject->typeAudit->id }}">{{ $record->aspect->subject->typeAudit->name }}</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">{{ __('Subject Audit') }}</label>
        <div class="col-sm-9 parent-group">
            <input type="text" name="object_id" value="{{ $record->aspect->subject->id }}" hidden>
            <select disabled name="object_id" id="subjectAudit" class="form-control base-plugin--select2-ajax subjectAudit"
                data-url="{{ rut('ajax.selectStruct', ['search' => 'subject', 'type_id' => '']) }}"
                data-url-origin="{{ rut('ajax.selectStruct', 'subject') }}" disabled
                placeholder="{{ __('Pilih Salah Satu') }}">
                <option select value="{{ $record->aspect->subject->id }}">{{ $record->aspect->subject->name }}</option>
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
                <option select value="{{ $record->aspect->mainProcess->id }}">{{ $record->aspect->mainProcess->name }}</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">{{ __('Lingkup Audit') }}</label>
        <div class="col-9 parent-group">
            <input type="text" name="aspect_id" value="{{ $record->aspect_id }}" hidden>
            <select disabled name="aspect_id"class="form-control base-plugin--select2-ajax aspect_id"
                data-url="{{ rut('ajax.selectAspect', ['search' => 'by_subject', 'type_id' => '', 'subject_id' => '']) }}"
                data-url-origin="{{ rut('ajax.selectAspect', 'by_subject') }}" disabled
                placeholder="{{ __('Pilih Salah Satu') }}">
                <option select value="{{ $record->aspect_id }}">{{ $record->aspect->name }}</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">{{ __('Nama') }}</label>
        <div class="col-9 parent-group">
            <input type="text" value="{{ $record->name }}" class="form-control" disabled>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">{{ __('Deskripsi') }}</label>
        <div class="col-9 parent-group">
            <textarea class="form-control" disabled>{{ $record->description }}</textarea>
        </div>
    </div>
@endsection

@section('buttons')
@endsection

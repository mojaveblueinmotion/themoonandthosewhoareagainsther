@extends('layouts.modal')

@section('modal-body')
<div class="form-group row">
    <label class="col-md-3 col-form-label">{{ __('Jenis Audit') }}</label>
    <div class="col-md-9 parent-group">
        <select data-post="type_id" class="form-control filter-control base-plugin--select2-ajax type_id"
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
    <label class="col-md-3 col-form-label">{{ __('Subject Audit') }}</label>
    <div class="col-md-9 parent-group">
        <select name="subject_id" id="subject_id" class="form-control base-plugin--select2-ajax subject_id"
            data-placeholder="{{ __('Pilih Salah Satu') }}" disabled>
            @isset($record->subject)
            <option value="{{$record->subject->id}}" class="form-control">{{$record->subject->name}}</option>
            @endisset
        </select>
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">{{ __('Main Process') }}</label>
    <div class="col-sm-9 parent-group">
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
<div class="form-group row">
    <label class="col-md-3 col-form-label">{{ __('Nama') }}</label>
    <div class="col-md-9 parent-group">
        <input name="name" class="form-control" id="nameCtrl" maxlength="255" placeholder="{{ __('Nama') }}" value="{{$record->name}}" disabled>
    </div>
</div>
<div class="form-group row">
    <label class="col-md-3 col-form-label">{{ __('Deskripsi') }}</label>
    <div class="col-md-9 parent-group">
        <textarea name="description" class="form-control" placeholder="{{ __('Deskripsi') }}" disabled>{{$record->description}}</textarea>
    </div>
</div>
@endsection

@section('buttons')
@endsection

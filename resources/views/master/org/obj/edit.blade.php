@extends('layouts.modal')

@section('action', rut($routes.'.update', $record->id))

@section('modal-body')
	@method('PATCH')
   <div class="form-group row">
        <label class="col-sm-3 col-form-label">{{ __('Jenis Audit') }}</label>
        <div class="col-sm-9 parent-group">
            <input type="text" name="type_id" value="{{ $record->aspect->subject->typeAudit->id }}" hidden>
            <select name="type_id" class="form-control filter-control base-plugin--select2-ajax type_id"
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
            <select name="object_id" id="subjectAudit" class="form-control base-plugin--select2-ajax subjectAudit"
                data-url="{{ rut('ajax.selectStruct', ['search' => 'subject', 'type_id' => '']) }}"
                data-url-origin="{{ rut('ajax.selectStruct', 'subject') }}" disabled
                placeholder="{{ __('Pilih Salah Satu') }}">
				<option select value="{{ $record->aspect->subject->id }}">{{ $record->aspect->subject->name }}</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">{{ __('Lingkup Audit') }}</label>
        <div class="col-9 parent-group">
            <input type="text" name="aspect_id" value="{{ $record->aspect_id }}" hidden>
            <select name="aspect_id"class="form-control base-plugin--select2-ajax aspect_id"
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
			<input type="text" name="name" class="form-control" placeholder="{{ __('Nama') }}" value="{{ $record->name }}">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-3 col-form-label">{{ __('Deskripsi') }}</label>
		<div class="col-9 parent-group">
			<textarea name="description" class="form-control" placeholder="{{ __('Deskripsi') }}">{{ $record->description }}</textarea>
		</div>
	</div>
@endsection

@extends('layouts.modal')

@section('action', rut($routes.'.update', $record->id))

@section('modal-body')
@method('PATCH')
<div class="form-group row">
	<label class="col-sm-4 col-form-label">{{ __('Versi') }}</label>
	<div class="col-sm-8 parent-group col-form-label">
		{!! $record->labelVersion() !!}
	</div>
</div>
@if (in_array($record->status, ['active','nonactive']))
<div class="form-group row">
	<label class="col-md-4 col-form-label">{{ __('Status') }}</label>
	<div class="col-md-8 parent-group col-form-label">
		<div class="radio-inline">
			<label class="radio radio-primary">
				<input type="radio" name="status" {{ $record['status']=='active' ? 'checked' : '' }} value="active">
				<span></span>Aktif
			</label>
			<label class="radio radio-primary">
				<input type="radio" name="status" {{ $record['status']=='active' ? '' : 'checked' }} value="nonactive">
				<span></span>Nonaktif
			</label>
		</div>
	</div>
</div>
<div class="form-group row">
	<label class="col-md-4 col-form-label">{{ __('Deskripsi') }}</label>
	<div class="col-md-8 parent-group">
		<textarea name="description" class="form-control"
			placeholder="{{ __('Deskripsi') }}">{!! $record->description !!}</textarea>
	</div>
</div>
@else
<div class="form-group row">
	<label class="col-sm-4 col-form-label">{{ __('Status') }}</label>
	<div class="col-sm-8 parent-group col-form-label">
		{!! $record->labelStatus() !!}
	</div>
</div>
<div class="form-group row">
	<label class="col-md-4 col-form-label">{{ __('Deskripsi') }}</label>
	<div class="col-md-8 parent-group">
		<textarea name="description" class="form-control"
			placeholder="{{ __('Deskripsi') }}">{!! $record->description !!}</textarea>
	</div>
</div>
@endif
@endsection

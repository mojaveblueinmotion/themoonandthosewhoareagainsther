@extends('layouts.modal')

@section('action', rut($routes.'.store'))

@section('modal-body')
	@method('POST')
	<div class="form-group row">
		<label class="col-sm-4 col-form-label">{{ __('Versi') }}</label>
		<div class="col-sm-8 parent-group col-form-label">
			{!! \Base::makeLabel($record->getNewVersion(), 'info') !!}
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-4 col-form-label">{{ __('Status') }}</label>
		<div class="col-sm-8 parent-group col-form-label">
			{!! $record->labelStatus('new') !!}
		</div>
	</div>
	<div class="form-group row">
		<label class="col-md-4 col-form-label">{{ __('Deskripsi') }}</label>
		<div class="col-md-8 parent-group">
			<textarea name="description"
				class="form-control"
				placeholder="{{ __('Deskripsi') }}"></textarea>
		</div>
	</div>
@endsection

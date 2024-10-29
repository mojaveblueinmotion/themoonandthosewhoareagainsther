@extends('layouts.modal')

@section('action', rut($routes.'.store'))

@section('modal-body')
	@method('POST')
	<div class="form-group row">
		<label class="col-md-12 col-form-label">{{ __('Jenis Audit') }}</label>
		<div class="col-md-12 parent-group">
			<input type="text" name="name" class="form-control" placeholder="{{ __('Jenis Audit') }}">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-md-12 col-form-label">{{ __('Deskripsi') }}</label>
		<div class="col-md-12 parent-group">
			<textarea name="description" class="form-control" placeholder="{{ __('Deskripsi') }}"></textarea>
		</div>
	</div>
@endsection

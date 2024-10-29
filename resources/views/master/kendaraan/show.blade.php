@extends('layouts.modal')

@section('modal-body')
	<div class="form-group row">
		<label class="col-md-12 col-form-label">{{ __('Nama') }}</label>
		<div class="col-md-12 parent-group">
			<input type="text" value="{{ $record->name }}" class="form-control" disabled>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-md-12 col-form-label">{{ __('Plat Nomor') }}</label>
		<div class="col-md-12 parent-group">
			<input type="text" name="no_kendaraan" class="form-control" disabled placeholder="{{ __('Plat Nomor') }}" value="{{ $record->no_kendaraan }}">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-md-12 col-form-label">{{ __('Deskripsi') }}</label>
		<div class="col-md-12 parent-group">
			<textarea class="form-control" disabled>{{ $record->description }}</textarea>
		</div>
	</div>
@endsection

@section('buttons')
@endsection

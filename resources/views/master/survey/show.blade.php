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
		{!! $record->labelStatus() !!}
	</div>
</div>
<div class="form-group row">
	<label class="col-md-4 col-form-label">{{ __('Deskripsi') }}</label>
	<div class="col-md-8 parent-group">
		<textarea name="description" class="form-control"
			placeholder="{{ __('Deskripsi') }}" disabled>{!! $record->description !!}</textarea>
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
			placeholder="{{ __('Deskripsi') }}" disabled>{!! $record->description !!}</textarea>
	</div>
</div>
@endif
@endsection

@section('buttons')
@endsection

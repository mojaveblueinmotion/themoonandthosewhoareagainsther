@extends('layouts.modal')

@section('action', route($routes.'.store'))
@php
$options = [
"format" => "mm/yyyy",
];
@endphp

@section('modal-body')
@method('POST')

<div class="form-group row">
	<label class="col-md-3 col-form-label">{{ __('Periode') }}</label>
	<div class="col-md-9 parent-group">
		<input type="text" name="periode" class="form-control base-plugin--datepicker-2 periode"
			data-options='@json($options)' placeholder="{{ __('Periode') }}">
	</div>
</div>
<div class="form-group row">
	<label class="col-md-3 col-form-label">{{ __('Subject Audit') }}</label>
	<div class="col-md-9 parent-group">
		<select id="unitKerjaCtrl" name="unit_kerja_id" class="form-control base-plugin--select2-ajax"
			data-url="{{ rut('ajax.selectStruct', 'all') }}" placeholder="{{ __('Pilih Salah Satu') }}">
			<option value="">{{ __('Pilih Salah Satu') }}</option>
		</select>
	</div>
</div>
<div class="form-group row">
	<label class="col-md-3 col-form-label">{{ __('Sasaran') }}</label>
	<div class="col-md-9 parent-group">
		<input type="text" name="sasaran" class="form-control" placeholder="{{ __('Sasaran') }}">
	</div>
</div>

@endsection

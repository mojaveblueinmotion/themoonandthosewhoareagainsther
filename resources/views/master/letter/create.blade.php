@extends('layouts.modal')

@section('action', rut($routes.'.store'))

@section('modal-body')
	@method('POST')
	<div class="form-group row">
		<label class="col-sm-12 col-form-label">{{ __('Parent') }}</label>
		<div class="col-sm-12 parent-group">
			<select name="type" class="form-control base-plugin--select2"
				data-placeholder="{{ __('Pilih Salah Satu') }}">
				<option value="assignment">{{ __('Surat Penugasan') }}</option>
				<option value="report">{{ __('LHA') }}</option>
			</select>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-12 col-form-label">{{ __('No. Surat') }}</label>
		<div class="col-sm-12 parent-group">
			<div class="input-group">
				<div class="input-group-prepend">
					<span class="input-group-text">[NO]/</span>
				</div>
				<input type="text" class="form-control" name="format" placeholder="{{ __('No. Surat') }}">
				<div class="input-group-append">
					<span class="input-group-text">/[MONTH]/[YEAR]</span>
				</div>
			</div>
			<div class="form-text text-muted">*Example: [NO]/ST-IA/OPR/[MONTH]/[YEAR]</div>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-md-3 col-form-label">{{ __('No Formulir') }}</label>
		<div class="col-md-9 parent-group">
			<div class="input-group">
				<input type="text" class="form-control" name="no_formulir" placeholder="{{ __('No Formulir') }}">
				<input type="text" class="form-control" name="no_formulir_tambahan" placeholder="{{ __('No Formulir') }}">
			</div>
		</div>
	</div>
@endsection

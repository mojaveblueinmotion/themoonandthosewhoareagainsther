@extends('layouts.modal')

@section('action', rut($routes.'.update', $record->id))

@section('modal-body')
	@method('PATCH')
	<div class="form-group row">
		<label class="col-md-3 col-form-label">{{ __('Menu') }}</label>
		<div class="col-md-9 parent-group">
			<input type="text" class="form-control" value="{{ $record->show_type }}" disabled>
		</div>
	</div>
	{{--<div class="form-group row">
		<label class="col-md-3 col-form-label">{{ __('No. Surat') }}</label>
		<div class="col-md-9 parent-group">
			<div class="input-group">
				<div class="input-group-prepend">
					<span class="input-group-text">[NO]/</span>
				</div>
				<input type="text" class="form-control" name="format" placeholder="{{ __('No. Surat') }}" value="{{ $record->format_center }}">
				<div class="input-group-append">
					<span class="input-group-text">/[MONTH]/[YEAR]</span>
				</div>
			</div>
		</div>
	</div>--}}
	<div class="form-group row">
		<label class="col-md-3 col-form-label">{{ __('No Formulir') }}</label>
		<div class="col-md-9 parent-group">
			<div class="input-group">
				<input type="text" class="form-control" name="no_formulir" placeholder="{{ __('No Formulir') }}" value="{{ $record->no_formulir }}">
				<input type="text" class="form-control" name="no_formulir_tambahan" placeholder="{{ __('No Formulir') }}" value="{{ $record->no_formulir_tambahan }}">
			</div>
		</div>
	</div>
	<div class="form-group row">
        <label class="col-sm-3 col-form-label">{{ __('Status') }}</label>
        <div class="col-sm-9 parent-group">
            <select name="is_available" class="form-control base-plugin--select2" placeholder="{{ __('Status') }}">
                <option value="active" @if($record->is_available == 'active') selected @endif>Tersedia</option>
                <option value="noactive" @if($record->is_available == 'noactive') selected @endif>Tidak Tersedia</option>
            </select>
        </div>
    </div>
@endsection

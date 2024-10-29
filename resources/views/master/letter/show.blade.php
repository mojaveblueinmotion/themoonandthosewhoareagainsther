@extends('layouts.modal')

@section('modal-body')
	<div class="form-group row">
		<label class="col-md-3 col-form-label">{{ __('Tipe') }}</label>
		<div class="col-md-9 parent-group">
			<input type="text" class="form-control" value="{{ $record->show_type }}" disabled>
		</div>
	</div>
	{{--<div class="form-group row">
		<label class="col-md-3 col-form-label">{{ __('No. Surat') }}</label>
		<div class="col-md-9 parent-group">
			<input type="text" class="form-control" value="{{ $record->format }}" disabled>
		</div>
	</div>--}}
	<div class="form-group row">
		<label class="col-md-3 col-form-label">{{ __('No Formulir') }}</label>
		<div class="col-md-9 parent-group">
			<div class="input-group">
				<input disabled type="text" class="form-control" name="no_formulir" placeholder="{{ __('No Formulir') }}" value="{{ $record->no_formulir }}">
				<input disabled type="text" class="form-control" name="no_formulir_tambahan" placeholder="{{ __('No Formulir') }}" value="{{ $record->no_formulir_tambahan }}">
			</div>
		</div>
	</div>
	<div class="form-group row">
        <label class="col-sm-3 col-form-label">{{ __('Status') }}</label>
        <div class="col-sm-9 parent-group">
            <select name="is_available" class="form-control base-plugin--select2" placeholder="{{ __('Status') }}" disabled>
                <option value="active" @if($record->is_available == 1) selected @endif>Tersedia</option>
                <option value="noactive" @if($record->is_available == 0) selected @endif>Tidak Tersedia</option>
            </select>
        </div>
    </div>
@endsection

@section('buttons')
@endsection

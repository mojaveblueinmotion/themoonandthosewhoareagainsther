@extends('layouts.modal')

@section('action', rut($routes.'.statementStore', $record->id))

@section('modal-body')
	@method('POST')
    <div class="form-group row">
		<label class="col-md-12 col-form-label">{{ __('Kategori') }}</label>
		<div class="col-md-12 parent-group">
			<select name="category_id"
                class="form-control base-plugin--select2"
                placeholder="{{ __('Pilih Salah Satu') }}">
                <option value="">{{ __('Pilih Salah Satu') }}</option>
                @foreach ($category as $val)
                    <option value="{{ $val->id }}">{{ $val->name }}</option>
                @endforeach
            </select>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-md-12 col-form-label">{{ __('Pernyataan') }}</label>
		<div class="col-md-12 parent-group">
			<textarea name="statement"
				class="form-control base-plugin--summernote"
				placeholder="{{ __('Pernyataan') }}" data-height="150"></textarea>
		</div>
	</div>
@endsection

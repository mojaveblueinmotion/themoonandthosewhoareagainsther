@extends('layouts.modal')

@section('action', rut($routes.'.statementUpdate', $statement->id))

@section('modal-body')
	@method('PATCH')
    <div class="form-group row">
		<label class="col-md-12 col-form-label">{{ __('Kategori') }}</label>
		<div class="col-md-12 parent-group">
			<select name="category_id"
                class="form-control base-plugin--select2"
                placeholder="{{ __('Pilih Salah Satu') }}">
                <option value="">{{ __('Pilih Salah Satu') }}</option>
                @foreach ($category as $val)
                    <option value="{{ $val->id }}" @if($val->id == $statement->category_id) selected @endif>{{ $val->name }}</option>
                @endforeach
            </select>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-md-12 col-form-label">{{ __('Pernyataan') }}</label>
		<div class="col-md-12 parent-group">
			<textarea name="statement"
				class="form-control base-plugin--summernote"
				placeholder="{{ __('Pernyataan') }}" data-height="150">{!! $statement->statement !!}</textarea>
		</div>
	</div>
@endsection

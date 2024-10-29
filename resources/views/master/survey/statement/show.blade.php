@extends('layouts.modal')

@section('modal-body')
<div class="form-group row">
    <label class="col-md-12 col-form-label">{{ __('Kategori') }}</label>
    <div class="col-md-12 parent-group">
        <input type="text" name="category_id" id="category_id" class="form-control" value="{{$statement->kategori->name}}" readonly>
    </div>
</div>
<div class="form-group row">
    <label class="col-md-12 col-form-label">{{ __('Pernyataan') }}</label>
    <div class="col-md-12 parent-group">
        <textarea name="statement"
        class="form-control base-plugin--summernote"
        disabled
        placeholder="{{ __('Pernyataan') }}" data-height="150">{!! $statement->statement !!}</textarea>
    </div>
</div>
@endsection

@section('buttons')
@endsection

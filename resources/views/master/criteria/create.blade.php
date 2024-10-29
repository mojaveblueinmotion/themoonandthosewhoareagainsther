@extends('layouts.modal')

@section('action', rut($routes . '.store'))

@section('modal-body')
    @method('POST')
    <div class="form-group row">
        <label class="col-md-3 col-form-label">{{ __('Nama') }}</label>
        <div class="col-md-9 parent-group">
            <input type="text" name="name" class="form-control" id="nameCtrl" maxlength="255"
                placeholder="{{ __('Nama') }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 col-form-label">{{ __('Deskripsi') }}</label>
        <div class="col-md-9 parent-group">
            <textarea name="description" class="form-control" id="descCtrl" placeholder="{{ __('Deskripsi') }}"></textarea>
        </div>
    </div>
@endsection

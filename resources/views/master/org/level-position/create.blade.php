@extends('layouts.modal')

@section('action', rut($routes . '.store'))

@section('modal-body')
    @method('POST')
    <div class="form-group row">
        <label class="col-md-12 col-form-label">{{ __('Nama') }}</label>
        <div class="col-md-12 parent-group">
            <input id="nameCtrl" type="text" name="name" class="form-control" maxlength="255" placeholder="{{ __('Nama') }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-12 col-form-label">{{ __('Deskripsi') }}</label>
        <div class="col-md-12 parent-group">
            <textarea id="descCtrl" name="description" class="form-control" placeholder="{{ __('Deskripsi') }}"></textarea>
        </div>
    </div>
@endsection

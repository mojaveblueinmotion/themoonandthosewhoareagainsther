@extends('layouts.modal')

@section('modal-body')
    <div class="form-group row">
        <label class="col-sm-12 col-form-label">{{ __('Parent') }}</label>
        <div class="col-sm-12 parent-group">
            <input type="text" value="{{ $record->parent->name ?? '' }}" class="form-control"
                placeholder="{{ __('Parent') }}" disabled>
            <div class="form-text text-muted">*Parent berupa Departemen/Divisi</div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-12 col-form-label">{{ __('Kode') }}</label>
        <div class="col-sm-3 parent-group">
            <input type="text" class="form-control" value="{{ $record->parent->code }}" disabled id="parentCode" id="parentCode" placeholder="{{ __('Kode') }}">
        </div>
        <div class="col-sm-9 parent-group">
            <input type="text" name="code" value="{{ substr($record->code, -2) }}" class="form-control"
                placeholder="{{ __('Kode') }}" disabled>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-12 col-form-label">{{ __('Nama') }}</label>
        <div class="col-sm-12 parent-group">
            <input type="text" value="{{ $record->name }}" class="form-control" placeholder="{{ __('Nama') }}"
                disabled>
        </div>
    </div>
@endsection

@section('buttons')
@endsection

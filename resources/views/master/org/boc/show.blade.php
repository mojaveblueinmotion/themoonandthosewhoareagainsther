@extends('layouts.modal')

@section('modal-body')
    <div class="form-group row">
        <label class="col-sm-12 col-form-label">{{ __('Parent') }}</label>
        <div class="col-sm-12 parent-group">
            <input type="text" value="{{ $record->parent->name ?? '' }}" class="form-control"
                placeholder="{{ __('Parent') }}" disabled>
            <div class="form-text text-muted">*Parent berupa Perusahaan</div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-12 col-form-label">{{ __('Kode') }}</label>
        <div class="col-sm-12 parent-group">
            <input type="text" name="code" value="{{ $record->code }}" class="form-control"
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

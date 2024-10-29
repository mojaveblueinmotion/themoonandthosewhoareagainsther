@extends('layouts.modal')

@section('modal-body')
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Kode') }}</label>
        <div class="col-sm-8 parent-group">
            <input readonly name="code" class="form-control" value="{{ $record->code }}"
                placeholder="{{ __('Kode') }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Jenis Audit') }}</label>
        <div class="col-sm-8 parent-group">
            <select name="type_id" data-post="type_id"
                class="form-control filter-control base-plugin--select2-ajax filter-type-id"
                data-url="{{ rut('ajax.selectTypeAudit', ['search' => 'all']) }}"
                placeholder="{{ __('Pilih Salah Satu ') }}" disabled>
                <option value="{{ $record->typeAudit->id }}">{{ $record->typeAudit->name }}</option>
            </select>
        </div>
    </div>
    {{-- <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Dept Auditee') }}</label>
        <div class="col-sm-8 parent-group">
            <select class="form-control base-plugin--select2-ajax unitKerja" id="unitKerja"
                data-placeholder="{{ __('Pilih Salah Satu') }}" disabled multiple disabled>
                @foreach ($record->childOfGroup as $item)
                    <option selected>{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
    </div> --}}
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Nama') }}</label>
        <div class="col-sm-8 parent-group">
            <input class="form-control" value="{{ $record->name ?? '' }}" disabled>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Deskripsi') }}</label>
        <div class="col-sm-8 parent-group">
            <textarea name="description" class="form-control" placeholder="{{ __('Deskripsi') }}" disabled>{{ $record->description }}</textarea>
        </div>
    </div>
@endsection

@section('buttons')
@endsection

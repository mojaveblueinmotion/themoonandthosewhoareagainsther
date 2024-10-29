@extends('layouts.modal')

@section('action', rut($routes . '.update', $record->id))

@section('modal-body')
    @method('PATCH')
    <div class="form-group row">
        <label class="col-sm-12 col-form-label">{{ __('Parent') }}</label>
        <div class="col-sm-12 parent-group">
            <select name="parent_id" class="form-control base-plugin--select2-ajax"
                data-url="{{ rut('ajax.selectStruct', ['search' => 'parent_bod', 'not' => $record->id]) }}"
                data-placeholder="{{ __('Pilih Salah Satu') }}">
                @if ($record->parent && $record->id != $record->parent->id)
                    <option value="{{ $record->parent->id }}" selected>{{ $record->parent->name }}</option>
                @endif
            </select>
            <div class="form-text text-muted">*Parent berupa Perusahaan/Direksi</div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-12 col-form-label">{{ __('Kode') }}</label>
        <div class="col-sm-12 parent-group">
            <input id="nameCtrl" type="text" value="{{ $record->code }}" name="code" class="form-control" placeholder="{{ __('Kode') }}" oninput="validateInput(this)">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-12 col-form-label">{{ __('Nama') }}</label>
        <div class="col-sm-12 parent-group">
            <input type="text" name="name" value="{{ $record->name }}" class="form-control"
                placeholder="{{ __('Nama') }}">
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function validateInput(input) {
        input.value = input.value.replace(/\D/g, '');
        if (input.value.length > 2) {
            input.value = input.value.slice(0, 2);
        }
    }
</script>
@endpush

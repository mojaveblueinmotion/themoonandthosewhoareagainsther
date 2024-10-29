@extends('layouts.modal')

@section('action', rut($routes . '.store'))

@section('modal-body')
    @method('POST')
    <div class="form-group row">
        <label class="col-sm-12 col-form-label">{{ __('Parent') }}</label>
        <div class="col-sm-12 parent-group">
            <select name="parent_id" class="form-control base-plugin--select2-ajax"
                data-url="{{ rut('ajax.selectStruct', 'parent_boc') }}" data-placeholder="{{ __('Pilih Salah Satu') }}">
            </select>
            <div class="form-text text-muted">*Parent berupa Perusahaan</div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-12 col-form-label">{{ __('Kode') }}</label>
        <div class="col-sm-12 parent-group">
            <input id="nameCtrl" type="text" name="code" class="form-control" placeholder="{{ __('Kode') }}" oninput="validateInput(this)">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-12 col-form-label">{{ __('Nama') }}</label>
        <div class="col-sm-12 parent-group">
            <input id="codeCtrl" type="text" name="name" class="form-control" placeholder="{{ __('Nama') }}">
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

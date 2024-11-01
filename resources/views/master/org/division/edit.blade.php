@extends('layouts.modal')

@section('action', rut($routes . '.update', $record->id))

@section('modal-body')
    @method('PATCH')
    <div class="form-group row">
        <label class="col-sm-12 col-form-label">{{ __('Parent') }}</label>
        <div class="col-sm-12 parent-group">
            <select name="parent_id" class="form-control base-plugin--select2-ajax" id="parentId"
                data-url="{{ rut('ajax.selectStruct', 'parent_division') }}" data-placeholder="{{ __('Pilih Salah Satu') }}">
                @if ($record->parent)
                    <option value="{{ $record->parent->id }}" selected>{{ $record->parent->name }}</option>
                @endif
            </select>
            <div class="form-text text-muted">*Parent berupa Departemen</div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-12 col-form-label">{{ __('Kode') }}</label>
        <div class="col-sm-3 parent-group">
            <input type="text" class="form-control" value="{{ $record->parent->code }}" disabled id="parentCode" id="parentCode" placeholder="{{ __('Kode') }}">
        </div>
        <div class="col-sm-9 parent-group">
            <input type="text" name="code" value="{{ substr($record->code, -2) }}" class="form-control masking-code"
                placeholder="{{ __('Kode') }}">
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
        $(".masking-code").inputmask({
            "mask": "9",
            "repeat": 2,
            "greedy": false
        });

        $(function() {
            $('.content-page').on('change', '#parentId', function() {
                $.ajax({
                    method: 'GET',
                    url: '{{ yurl('/ajax/getStruct') }}',
                    data: {
                        id: $(this).val()
                    },
                    success: function(response, state, xhr) {
                        $('#parentCode').val(response.code);
                    },
                    error: function(a, b, c) {
                        console.log(a, b, c);
                    }
                });
            });
        });
	</script>
@endpush
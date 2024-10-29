@extends('layouts.modal')

@section('modal-body')
    <div class="form-group row">
        <label class="col-md-3 col-form-label">{{ __('Level Kemungkinan') }}</label>
        <div class="col-md-9 parent-group">
            <select disabled required name="level_kemungkinan_id" class="form-control base-plugin--select2-ajax"
                data-url="{{ rut('ajax.selectLevelKemungkinan', 'all') }}" placeholder="{{ __('Pilih Salah Satu') }}">
                <option value="">{{ __('Pilih Salah Satu') }}</option>
                @if (!empty($detail->levelKemungkinan))
                    <option value="{{ $detail->levelKemungkinan->id }}" selected>
                        {{ $detail->levelKemungkinan->name }}</option>
                @endif
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 col-form-label">{{ __('Level Dampak') }}</label>
        <div class="col-md-9 parent-group">
            <select disabled required name="level_dampak_id" class="form-control base-plugin--select2-ajax"
                data-url="{{ rut('ajax.selectLevelDampak', 'all') }}" placeholder="{{ __('Pilih Salah Satu') }}">
                <option value="">{{ __('Pilih Salah Satu') }}</option>
                @if (!empty($detail->levelDampak))
                    <option value="{{ $detail->levelDampak->id }}" selected>
                        {{ $detail->levelDampak->name }}</option>
                @endif
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 col-form-label">{{ __('Tingkat Resiko') }}</label>
        <div class="col-md-9 parent-group">
            <select disabled required name="tingkat_resiko_id" class="form-control base-plugin--select2-ajax"
                data-url="{{ rut('ajax.selectRiskRating') }}" placeholder="{{ __('Pilih Salah Satu') }}">
                <option value="">{{ __('Pilih Salah Satu') }}</option>
                @if (!empty($detail->tingkatResiko))
                    <option value="{{ $detail->tingkatResiko->id }}" selected>
                        {{ $detail->tingkatResiko->name }}</option>
                @endif
            </select>
        </div>
    </div>
@endsection

<script>
    $('.modal-dialog').removeClass('modal-md').addClass('modal-lg');
</script>

@section('buttons')
@endsection

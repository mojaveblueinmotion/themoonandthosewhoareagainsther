@extends('layouts.modal')

@section('modal-body')
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">{{ __('ID Resiko') }}</label>
        <div class="col-sm-9 parent-group">
            <input type="text" name="id_resiko" class="form-control" readonly value="{{ $detail->id_resiko }}" placeholder="{{ __('ID Resiko') }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">{{ __('Main Process') }}</label>
        <div class="col-9 parent-group">
            <select disabled name="main_process_id" class="form-control base-plugin--select2-ajax"
                data-url="{{ rut('ajax.selectMainProcess', ['search' => 'all']) }}"
                placeholder="{{ __('Pilih Salah Satu') }}">
                <option value="">{{ __('Pilih Salah Satu') }}</option>
                <option value="{{ $detail->kodeResiko->id }}" selected>{{ $detail->kodeResiko->name }}</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">{{ __('Sub Process') }}</label>
        <div class="col-9 parent-group">
            <select disabled name="sub_process_id" class="form-control base-plugin--select2-ajax"
                data-url="{{ rut('ajax.selectAspect', ['search' => 'all']) }}"
                placeholder="{{ __('Pilih Salah Satu', ['search' => 'all']) }}">
                <option value="">{{ __('Pilih Salah Satu') }}</option>
                <option value="{{ $detail->jenisResiko->id }}" selected>{{ $detail->jenisResiko->name }}</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">{{ __('Proses Objective') }}</label>
        <div class="col-9 parent-group">
            <textarea required disabled name="objective" class="base-plugin--summernote" placeholder="{{ __('Proses Objective') }}">{{ $detail->objective }}</textarea>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">{{ __('Risk Event') }}</label>
        <div class="col-9 parent-group">
            <textarea disabled name="peristiwa" class="base-plugin--summernote" placeholder="{{ __('Risk Event') }}">{{ $detail->peristiwa }}</textarea>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">{{ __('Risk Cause') }}</label>
        <div class="col-9 parent-group">
            <textarea disabled name="penyebab" class="base-plugin--summernote" placeholder="{{ __('Risk Cause') }}">{{ $detail->penyebab }}</textarea>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">{{ __('Risk Impact') }}</label>
        <div class="col-9 parent-group">
            <textarea disabled name="dampak" class="base-plugin--summernote" placeholder="{{ __('Risk Impact') }}">{{ $detail->dampak }}</textarea>
        </div>
    </div>
    @if (!empty($detail->inherentRisk->levelDampak))
        <div class="form-group row">
            <label class="col-3 col-form-label">{{ __('Level Dampak') }}</label>
            <div class="col-9 parent-group">
                <textarea disabled class="form-control" placeholder="{{ __('Level Dampak') }}">{{ $detail->inherentRisk->levelDampak->name }}</textarea>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-3 col-form-label">{{ __('Level Kemungkinan') }}</label>
            <div class="col-9 parent-group">
                <textarea disabled class="form-control" placeholder="{{ __('Level Kemungkinan') }}">{{ $detail->inherentRisk->levelKemungkinan->name }}</textarea>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-3 col-form-label">{{ __('Tingkat Resiko') }}</label>
            <div class="col-9 parent-group">
                <textarea disabled class="form-control" placeholder="{{ __('Tingkat Resiko') }}">{{ $detail->inherentRisk->tingkatResiko->name }}</textarea>
            </div>
        </div>
    @endif
@endsection

@section('buttons')
@endsection

@push('scripts')
    <script>
        $('.modal-dialog').removeClass('modal-md').addClass('modal-lg');
    </script>
@endpush

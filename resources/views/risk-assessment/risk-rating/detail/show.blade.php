@extends('layouts.modal')

@section('modal-body')
    <div class="form-group row">
        <label class="col-md-3 col-form-label">{{ __('Prosentase') }}</label>
        <div class="col-md-9 parent-group">
                <div class="input-group">
                    <input disabled name="prosentase" class="form-control angka--persen"
                    placeholder="{{ __('Prosentase') }}"
                    value="{{ $detail->residualRisk->prosentase }}">
                    <div class="input-group-prepend">
                        <div class="input-group-text">%</div>
                    </div>
                </div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 col-form-label">{{ __('Level Kemungkinan') }}</label>
        <div class="col-md-9 parent-group">
            <select disabled required name="level_kemungkinan_id"
                class="form-control base-plugin--select2-ajax"
                data-url="{{ rut('ajax.selectLevelKemungkinan', 'all') }}"
                placeholder="{{ __('Pilih Salah Satu') }}">
                <option value="">{{ __('Pilih Salah Satu') }}</option>
                @if (!empty($detail->residualRisk->levelKemungkinan))
                    <option value="{{ $detail->residualRisk->levelKemungkinan->id }}" selected>
                        {{ $detail->residualRisk->levelKemungkinan->name }}</option>
                @endif
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 col-form-label">{{ __('Level Dampak') }}</label>
        <div class="col-md-9 parent-group">
            <select disabled required name="level_dampak_id"
                class="form-control base-plugin--select2-ajax"
                data-url="{{ rut('ajax.selectLevelDampak', 'all') }}"
                placeholder="{{ __('Pilih Salah Satu') }}">
                <option value="">{{ __('Pilih Salah Satu') }}</option>
                @if (!empty($detail->residualRisk->levelDampak))
                    <option value="{{ $detail->residualRisk->levelDampak->id }}" selected>
                        {{ $detail->residualRisk->levelDampak->name }}</option>
                @endif
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 col-form-label">{{ __('Tingkat Resiko') }}</label>
        <div class="col-md-9 parent-group">
            <select disabled required name="tingkat_resiko_id"
                class="form-control base-plugin--select2-ajax"
                data-url="{{ rut('ajax.selectRiskRating') }}"
                placeholder="{{ __('Pilih Salah Satu') }}">
                <option value="">{{ __('Pilih Salah Satu') }}</option>
                @if (!empty($detail->residualRisk->tingkatResiko))
                    <option value="{{ $detail->residualRisk->tingkatResiko->id }}" selected>
                        {{ $detail->residualRisk->tingkatResiko->name }}</option>
                @endif
            </select>
        </div>
    </div>
    <hr>
	<div class="form-group row">
		<label class="col-md-3 col-form-label">{{ __('Internal Control') }}</label>
		<div class="col-md-9 parent-group">
			<textarea disabled required name="internal_control" class="form-control" placeholder="{{ __('Internal Control') }}">{{ $detail->internal_control }}</textarea>
		</div>
	</div>
	<div class="form-group row">
        <label class="col-md-3 col-form-label">{{ __('Tgl Realisasi') }}</label>
        <div class="col-md-9 parent-group">
            <input disabled type="text" name="tgl_realisasi"
                class="form-control base-plugin--datepicker tgl_realisasi"
                placeholder="{{ __('Tgl Realisasi') }}"
                value="{{ $detail->tgl_realisasi->format('d/m/Y')  }}"
                data-orientation="top"
                data-options='@json([
                    "startDate" => now()->format('d/m/Y'),
                    "endDate" => '',
                ])'
                autocomplete="off">
        </div>
    </div>
	<div class="form-group row">
		<label class="col-md-3 col-form-label">{{ __('Realisasi') }}</label>
		<div class="col-md-9 parent-group">
			<textarea disabled required name="realisasi" class="form-control" placeholder="{{ __('Realisasi') }}">{{ $detail->realisasi }}</textarea>
		</div>
	</div>
@endsection

<script>
	$('.modal-dialog').removeClass('modal-md').addClass('modal-lg');
</script>


@section('buttons')
@endsection

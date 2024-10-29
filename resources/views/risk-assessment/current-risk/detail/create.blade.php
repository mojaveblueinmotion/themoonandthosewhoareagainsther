@extends('layouts.modal')

@section('action', route($routes.'.detailStore', $record->id))

@section('modal-body')
	@method('POST')
	<input type="hidden" name="current_risk_id" value="{{ $record->id }}">
	<div class="form-group row">
		<label class="col-md-3 col-form-label">{{ __('Internal Control') }}</label>
		<div class="col-md-9 parent-group">
			<textarea required name="internal_control" class="form-control" placeholder="{{ __('Internal Control') }}"></textarea>
		</div>
	</div>
	<div class="form-group row">
        <label class="col-md-3 col-form-label">{{ __('Tgl Realisasi') }}</label>
        <div class="col-md-9 parent-group">
            <input type="text" name="tgl_realisasi"
                class="form-control base-plugin--datepicker tgl_realisasi"
                placeholder="{{ __('Tgl Realisasi') }}"
                value=""
                data-orientation="top"
                data-options='@json([
                    "startDate" => '',
                    "endDate" => now()->format('d/m/Y'),
                ])'
                autocomplete="off">
        </div>
    </div>
	<div class="form-group row">
		<label class="col-md-3 col-form-label">{{ __('Realisasi') }}</label>
		<div class="col-md-9 parent-group">
			<textarea required name="realisasi" class="form-control" placeholder="{{ __('Realisasi') }}"></textarea>
		</div>
	</div>
@endsection

<script>
	$('.modal-dialog').removeClass('modal-md').addClass('modal-lg');
</script>

@extends('layouts.modal')

@section('action', route($routes.'.detailUpdate', $detail->id))

@section('modal-body')
	@method('PATCH')
	<input type="hidden" name="current_risk_id" value="{{ $detail->current_risk_id }}">
	<div class="form-group row">
		<label class="col-md-3 col-form-label">{{ __('Internal Control') }}</label>
		<div class="col-md-9 parent-group">
			<textarea required name="internal_control" class="form-control" placeholder="{{ __('Internal Control') }}">{{ $detail->internal_control }}</textarea>
		</div>
	</div>
	<div class="form-group row">
        <label class="col-md-3 col-form-label">{{ __('Tgl Realisasi') }}</label>
        <div class="col-md-9 parent-group">
            <input type="text" name="tgl_realisasi"
                class="form-control base-plugin--datepicker tgl_realisasi"
                placeholder="{{ __('Tgl Realisasi') }}"
                value="{{ $detail->tgl_realisasi->format('d/m/Y')  }}"
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
			<textarea required name="realisasi" class="form-control" placeholder="{{ __('Realisasi') }}">{{ $detail->realisasi }}</textarea>
		</div>
	</div>
@endsection

<script>
	$('.modal-dialog').removeClass('modal-md').addClass('modal-lg');
</script>

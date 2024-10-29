@extends('layouts.modal')

@section('modal-body')
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
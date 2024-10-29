@extends('layouts.modal')

@section('action', route($routes.'.update', $record->id))

@section('action', route($routes.'.store'))
@php
$options = [
"format" => "mm/yyyy",
];
@endphp

@section('modal-body')
@method('PATCH')
<div class="form-group row">
	<label class="col-md-3 col-form-label">{{ __('Periode') }}</label>
	<div class="col-md-9 parent-group">
		<input type="text" readonly name="periode" class="form-control base-plugin--datepicker-2 periode"
			data-options='@json($options)' placeholder="{{ __('Periode') }}" value="{{ $record->periode->format('m/Y') }}">
	</div>
</div>
<div class="form-group row">
	<label class="col-md-3 col-form-label">{{ __('Subject Audit') }}</label>
	<div class="col-md-9 parent-group">
		<select id="unitKerjaCtrl" name="unit_kerja_id" class="form-control base-plugin--select2-ajax"
			data-url="{{ rut('ajax.selectStruct', 'all') }}" placeholder="{{ __('Pilih Salah Satu') }}">
			<option value="">{{ __('Pilih Salah Satu') }}</option>
			@if (!empty($record->unitKerja))
			<option value="{{ $record->unitKerja->id }}" selected>{{ $record->unitKerja->name }}</option>
			@endif
		</select>
	</div>
</div>
<div class="form-group row">
	<label class="col-md-3 col-form-label">{{ __('Sasaran') }}</label>
	<div class="col-md-9 parent-group">
		<input type="text" name="sasaran" value="{{ $record->sasaran }}" class="form-control" placeholder="{{ __('Sasaran') }}">
	</div>
</div>
@endsection

{{-- @push('scripts')
<script>
	$(function () {
			$('.content-page').on('changeDate', 'input.date_start', function (value) {
				var me = $(this);
				if (me.val()) {
					var startDate = new Date(value.date.valueOf());
					var date_end = me.closest('.input-group').find('input.date_end');
					date_end.prop('disabled', false)
							.val(me.val())
							.datepicker('setStartDate', startDate)
							.focus();
				}
			});
		});
</script>
@endpush --}}

@push('scripts')
<script>
	$(function () {
			var toTime = function (date) {
				var ds = date.split('/');
				var year = ds[2];
				var month = ds[1];
				var day = ds[0];
				return new Date(year+'-'+month+'-'+day).getTime();
			}

			$('.content-page').on('changeDate', 'input.date-start-table', function (value) {
				var me = $(this),
					startDate = new Date(value.date.valueOf()),
					date_end = me.closest('.input-group').find('input.date-end-table');

				if (me.val()) {
					if (toTime(me.val()) > toTime(date_end.val())) {
						date_end.datepicker('update', '')
							.datepicker('setStartDate', startDate)
							.prop('disabled', false);
					}
					else {
						date_end.datepicker('update', date_end.val())
							.datepicker('setStartDate', startDate)
							.prop('disabled', false);
					}
				}
				else {
					date_end.datepicker('update', '')
						.prop('disabled', true);
				}
			});
		});
</script>
@endpush

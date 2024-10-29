@extends('layouts.form', ['container' => 'container'])

@section('action', rut($routes.'.update', $record->id))

@section('card-body')
	@method('PATCH')

	<div class="row">
		<div class="col-md-6">
			<div class="form-group row">
				<label class="col-md-4 col-form-label">{{ __('Tahun') }}</label>
				<div class="col-md-8 parent-group">
					<input type="text" name="year"
						class="form-control base-plugin--datepicker-3"
						data-orientation="bottom"
						value="{{ $record->year }}"
						placeholder="{{ __('Tahun') }}">
				</div>
			</div>
			<div class="form-group row">
				<label class="col-md-4 col-form-label">{{ __('Jenis Audit') }}</label>
				<div class="col-md-8 parent-group">
					<select name="type_id"  class="form-control base-plugin--select2-ajax type_id"
						data-url="{{ rut('ajax.selectTypeAudit', ['search' => 'all']) }}"
						placeholder="{{ __('Pilih Salah Satu ') }}">
						<option value="">{{ __('Jenis Audit') }}</option>
						@if ($type= $record->type)
							<option value="{{ $type->id }}" selected>{{ $type->name }}</option>
						@endif
					</select>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group row">
				<label class="col-md-4 col-form-label">{{ __('Subject Audit') }}</label>
				<div class="col-md-8 parent-group">
					<select name="object_id" class="form-control base-plugin--select2-ajax object_id"
						data-url="{{ rut('ajax.selectObject', ['category' => 'by_type', 'type_id'=>$record->type_id]) }}"
						data-url-origin="{{ rut('ajax.selectObject') }}"
						placeholder="{{ __('Pilih Salah Satu') }}">
						<option value="">{{ __('Pilih Salah Satu') }}</option>
						@if ($object = $record->getObject())
							<option value="{{ $object->id }}" selected>{{ $object->name }}</option>
						@endif
					</select>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-md-4 col-form-label">{{ __('Key Activities') }}</label>
				<div class="col-md-8 parent-group">
					<input type="text" name="key"
						class="form-control"
						value="{{ $record->key }}"
						placeholder="{{ __('Key Activities') }}">
				</div>
			</div>
		</div>
	</div>
	<hr>
	<div class="table-responsive">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th class="text-center width-60px">No</th>
					<th class="text-center">{{ __('Detail') }}</th>
					<th class="text-center width-200px">{{ __('Tingkat Risiko') }}</th>
					<th class="text-center">{{ __('Dasar Penentuan Tingkat Risiko') }}</th>
					<th class="text-center valign-middle width-60px">
						<button type="button"
							class="btn btn-sm btn-icon btn-circle btn-primary add-detail">
							<i class="fa fa-plus"></i>
						</button>
					</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($record->details as $detail)
					<tr data-key="{{ $loop->iteration }}">
						<td class="text-center width-60px no">{{ $loop->iteration }}</td>
						<td class="text-left parent-group">
							<input type="text" name="details[{{ $loop->iteration }}][description]"
								class="form-control"
								value="{{ $detail->description }}"
								placeholder="{{ __('Detail') }}">
						</td>
						<td class="text-left width-200px parent-group">
							<select name="details[{{ $loop->iteration }}][risk_rating_id]"
								class="form-control base-plugin--select2-ajax risk_rating_id"
								data-url="{{ rut('ajax.selectRiskRating', ['search'=>'all']) }}"
								style="width: 200px"
								placeholder="{{ __('Pilih Salah Satu') }}">
								<option value="">{{ __('Pilih Salah Satu') }}</option>
								@if ($riskRating = $detail->riskRating)
									<option value="{{ $riskRating->id }}" selected>{{ $riskRating->name }}</option>
								@endif
							</select>
						</td>
						<td class="text-left parent-group">
							<textarea name="details[{{ $loop->iteration }}][source]"
								class="form-control"
								placeholder="{{ __('Dasar Penentuan Tingkat Risiko') }}">{!! $detail->source !!}</textarea>
						</td>
						<td class="text-center valign-middle width-60px">
							<button type="button"
								@if($loop->count == 1) disabled @endif
								class="btn btn-sm btn-icon btn-circle btn-danger remove-detail">
								<i class="fa fa-trash"></i>
							</button>
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
@endsection

@section('card-footer')
	<div class="d-flex justify-content-between">
		@include('layouts.forms.btnBack')
		@include('layouts.forms.btnSubmitPage')
	</div>
@endsection

@push('scripts')
	<script>
		$(function () {
			handleTableDetail();

			$('.content-page').on('change', 'select.type_id', function(e) {
                var me = $(this);
                var objectId = $('select.object_id');

                if (me.val()) {
                    var urlOrigin = objectId.data('url-origin');
                    var urlParam = $.param({
                        category: 'by_type',
                        type_id: me.val()
                    });
                    objectId.data('url', decodeURIComponent(decodeURIComponent(urlOrigin + '?' + urlParam)));
                    objectId.val(null).prop('disabled', false);
                } else {
                    objectId.prop('disabled', true).val(null); // Disable and reset value
                }

                BasePlugin.initSelect2();
            });
		});

		var handleTableDetail = function () {
			$('.content-page').on('click', '.add-detail', function (e) {
				e.preventDefault();
				var me = $(this);
				var tbody = me.closest('table').find('tbody').first();
				var key = tbody.find('tr').last().length ? (tbody.find('tr').last().data('key') + 1) : 1;
				var template = `
					<tr class="animate__animated animate__fadeIn" data-key="`+key+`">
						<td class="text-center width-60px no">`+key+`</td>
						<td class="text-left parent-group">
							<input type="text" name="details[`+key+`][description]"
								class="form-control"
								placeholder="{{ __('Detail') }}">
						</td>
						<td class="text-left width-200px parent-group">
							<select name="details[`+key+`][risk_rating_id]"
								class="form-control base-plugin--select2-ajax risk_rating_id"
								data-url="{{ rut('ajax.selectRiskRating', ['search'=>'all']) }}"
								style="width: 200px"
								placeholder="{{ __('Pilih Salah Satu') }}">
								<option value="">{{ __('Pilih Salah Satu') }}</option>
							</select>
						</td>
						<td class="text-left parent-group">
							<textarea name="details[`+key+`][source]"
								class="form-control"
								placeholder="{{ __('Dasar Penentuan Tingkat Risiko') }}"></textarea>
						</td>
						<td class="text-center valign-middle width-60px">
							<button type="button"
								class="btn btn-sm btn-icon btn-circle btn-danger remove-detail">
								<i class="fa fa-trash"></i>
							</button>
						</td>
					</tr>
				`;

				tbody.append(template);

				tbody.find('.no').each(function (i, el) {
					$(el).html((i+1));
				});

				if (tbody.find('.remove-detail').length > 1) {
					tbody.find('.remove-detail').prop('disabled', false);
				}

				BasePlugin.initSelect2();
			});

			$('.content-page').on('click', '.remove-detail', function (e) {
				e.preventDefault();
				var me = $(this);
				var tbody = me.closest('table').find('tbody').first();

				me.closest('tr').remove();

				tbody.find('.no').each(function (i, el) {
					$(el).html((i+1));
				});

				if (tbody.find('.remove-detail').length == 1) {
					tbody.find('.remove-detail').prop('disabled', true);
				}

				BasePlugin.initSelect2();
			});
		}
	</script>
@endpush

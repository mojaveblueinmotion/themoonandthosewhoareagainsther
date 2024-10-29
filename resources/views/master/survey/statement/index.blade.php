@extends('layouts.page', ['container' => 'container'])

@section('card-body')
	@method('POST')
	<div class="row">
	    <div class="col-sm-6">
	        <div class="form-group row">
	            <label class="col-sm-4 col-form-label">{{ __('Versi') }}</label>
	            <div class="col-sm-8 col-form-label">
	                {!! $record->labelVersion() !!}
	            </div>
	        </div>
	    </div>
	    <div class="col-sm-12">
	        <div class="form-group row">
	            <label class="col-sm-2 col-form-label">{{ __('Deskripsi') }}</label>
	            <div class="col-sm-10">
	                <textarea class="form-control base-plugin--summernote"
	                    data-height="200px"
	                    disabled>{!! $record->description !!}</textarea>
	            </div>
	        </div>
	    </div>
	</div>
	<hr>
	<table id="dataFilters" class="width-full">
		<tbody>
			<tr>
				<td class="pb-2 valign-top td-filter-reset width-80px">
					<div class="reset-filter mr-1 hide">
						<button class="btn btn-secondary btn-icon width-full reset button" data-toggle="tooltip" data-original-title="Reset Filter"><i class="fas fa-sync"></i></button>
					</div>
					<div class="label-filter mr-1">
						<button class="btn btn-secondary btn-icon width-full filter button" data-toggle="tooltip" data-original-title="Filter"><i class="fas fa-filter"></i></button>
					</div>
				</td>
				<td>
					<div class="row">
						<div class="col-12 col-sm-6 col-xl-3 pb-2">
							<input type="text" class="form-control filter-control"
								data-post="statement"
								placeholder="{{ __('Pernyataan') }}">
						</div>
					</div>
				</td>
				<td class="text-right td-btn-create width-200px">
					@if (in_array($record->status, ['new','draft']))
						@if ($record->statements()->count())
							<a href="{{ rut($routes.'.activate', $record->id) }}"
								class="btn btn-primary mr-1 base-form--postByUrl"
								data-swal-text="{{ __('Setelah diaktivasi, item pernyataan survey tidak dapat diperbarui lagi!') }}">
								<i class="far fa-check-circle"></i> {{ __('Aktivasi') }}
							</a>
						@endif
						@include('layouts.forms.btnAdd', [
							'urlAdd' => rut($routes.'.statementCreate', $record->id)
						])
					@endif
				</td>
			</tr>
		</tbody>
	</table>
	<div class="table-responsive">
	    @if(isset($tableStruct['datatable_1']))
		    <table id="datatable_1" class="table table-bordered is-datatable" style="width: 100%;"
		    	data-url="{{ $tableStruct['url'] }}"
		    	data-paging="{{ $paging ?? true }}"
		    	data-info="{{ $info ?? true }}">
		        <thead>
		            <tr>
		                @foreach ($tableStruct['datatable_1'] as $struct)
		                	<th class="text-center v-middle"
		                		data-columns-name="{{ $struct['name'] ?? '' }}"
		                		data-columns-data="{{ $struct['data'] ?? '' }}"
		                		data-columns-label="{{ $struct['label'] ?? '' }}"
		                		data-columns-sortable="{{ $struct['sortable'] === true ? 'true' : 'false' }}"
		                		data-columns-width="{{ $struct['width'] ?? '' }}"
		                		data-columns-class-name="{{ $struct['className'] ?? '' }}"
		                		style="{{ isset($struct['width']) ? 'width: '.$struct['width'].'; ' : '' }}">
		                		{{ $struct['label'] }}
		                	</th>
		                @endforeach
		            </tr>
		        </thead>
		        <tbody>
		        </tbody>
		    </table>
		@endif
	</div>
@endsection

@section('buttons')
@endsection

@push('scripts')
@endpush

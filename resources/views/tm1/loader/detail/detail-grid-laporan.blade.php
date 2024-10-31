
<table id="dataFilters" class="width-full">
	<tbody>
		<tr>
			<td class="pb-2 valign-top td-filter-reset">
				<div class="reset-filter mr-1 hide">
					<button class="btn btn-secondary btn-icon width-full reset button" data-toggle="tooltip"
						data-original-title="Reset Filter"><i class="fas fa-sync"></i></button>
				</div>
				<div class="label-filter mr-1">
					<button class="btn btn-secondary btn-icon width-full filter button" data-toggle="tooltip"
						data-original-title="Filter"><i class="fas fa-filter"></i></button>
				</div>
			</td>
			<td>
				<div class="row">
					<div class="col-12 col-sm-3 col-xl-3 mr-n6 pb-2">
						<input class="form-control base-plugin--datepicker filter-control" data-post="tgl_input"
							data-options='@json([
								'startDate' => '01/' . $record->month->format('m/Y'),
								'endDate' => '01/' . $record->month->addMonth()->format('m/Y'),
							])'
							placeholder="{{ __('Tanggal Masuk') }}">
					</div>
				</div>
			</td>
			<td class="text-right td-btn-create width-200px">
				@if(request()->route()->getName() != $routes.'.show' && request()->route()->getName() != $routes.'.approval')
				<a href="{{ rut($routes.'.detailCreate', $record->id) }}" data-modal-position="default"
					class="btn btn-info ml-2 {{ empty($baseContentReplace) ? 'base-modal--render' : 'base-content--replace' }}"
					data-modal-backdrop="false"
					data-modal-v-middle="false">
					<i class="fa fa-plus"></i> Tambah
				</a>
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


@push('scripts')
    <script>
        $('.modal-dialog').removeClass('modal-md').addClass('modal-lg');
        $('.content-page')
            .on('change', 'select.main_process_id', function(e) {
                var me = $(this);
                var auditor = $('select.sub_process_id');
                var urlOrigin = auditor.data('url-origin');
                var urlParam = $.param({
                    search: 'find',
                    id: me.val(),
                });
                console.log(urlParam);
                console.log(urlOrigin);
                auditor.data('url', decodeURIComponent(decodeURIComponent(urlOrigin + '?' +
                        urlParam)));
                auditor.val(null).prop('disabled', false);

                BasePlugin.initSelect2();
            });
    </script>
@endpush

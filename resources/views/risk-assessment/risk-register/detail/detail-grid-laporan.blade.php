
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
					<div class="ml-6 pb-2" style="width: 250px">
						<select data-post="main_process_id" class="form-control filter-control base-plugin--select2-ajax main_process_id"
							data-url="{{ rut('ajax.selectMainProcess') }}"
							placeholder="{{ __('Main Process') }}">
							<option value="">{{ __('Main Process') }}</option>
						</select>
					</div>
					<div class="ml-4 pb-2" style="width: 250px">
						<select data-post="sub_process_id" class="form-control filter-control base-plugin--select2-ajax sub_process_id"
							data-url="{{ rut('ajax.selectAspect', ['search'=>'all']) }}"
							data-url-origin="{{ rut('ajax.selectAspect', ['search'=>'all']) }}"
							placeholder="{{ __('Sub Process') }}" disabled>
							<option value="">{{ __('Sub Process') }}</option>
						</select>
					</div>
				</div>
			</td>
			<td class="text-right td-btn-create width-200px">
				@if(request()->route()->getName() != $routes.'.show' && request()->route()->getName() != $routes.'.approval')
				<a href="{{ rut($routes.'.detailCreate', $record->id) }}"
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

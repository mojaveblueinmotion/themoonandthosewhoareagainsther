<table id="dataFilters" class="width-full">
    <tbody>
        <tr>
            <td class="valign-top td-filter-reset pb-2">
                <div class="reset-filter hide mr-1">
                    <button class="btn btn-secondary btn-icon width-full reset button" data-toggle="tooltip"
                        data-original-title="Reset Filter"><i class="fas fa-sync"></i></button>
                </div>
                <div class="label-filter mr-1">
                    <button class="btn btn-secondary btn-icon width-full button filter" data-toggle="tooltip"
                        data-original-title="Filter"><i class="fas fa-filter"></i></button>
                </div>
            </td>
            <td>
                <div class="row">
                    <div class="ml-4 pb-2" style="width:200px;">
                        <input type="text" name="tgl_realisasi" data-post = "tgl_realisasi"
                            class="form-control base-plugin--datepicker" placeholder="{{ __('Tgl Realisasi') }}"
                            data-orientation="bottom" data-options='@json([
                                'startDate' => null,
                                'endDate' => null,
                            ])'>
                    </div>
                </div>
            </td>
            <td class="td-btn-create width-200px text-right">
                @if (request()->route()->getName() !=
                        $routes . '.show' &&
                        request()->route()->getName() !=
                            $routes . '.approval')
                    <a href="{{ route($routes . '.detailCreate', $record->id) }}"
                        class="btn btn-info {{ empty($baseContentReplace) ? 'base-modal--render' : 'base-content--replace' }} ml-2"
                        data-modal-backdrop="false" data-modal-v-middle="false">
                        <i class="fa fa-plus"></i> Tambah
                    </a>
                @endif
            </td>
        </tr>
    </tbody>
</table>

<div class="table-responsive">
    @if (isset($tableStruct['datatable_1']))
        <table id="datatable_1" class="table-bordered is-datatable table" style="width: 100%;"
            data-url="{{ $tableStruct['url'] }}" data-paging="{{ $paging ?? true }}" data-info="{{ $info ?? true }}">
            <thead>
                <tr>
                    @foreach ($tableStruct['datatable_1'] as $struct)
                        <th class="v-middle text-center" data-columns-name="{{ $struct['name'] ?? '' }}"
                            data-columns-data="{{ $struct['data'] ?? '' }}"
                            data-columns-label="{{ $struct['label'] ?? '' }}"
                            data-columns-sortable="{{ $struct['sortable'] === true ? 'true' : 'false' }}"
                            data-columns-width="{{ $struct['width'] ?? '' }}"
                            data-columns-class-name="{{ $struct['className'] ?? '' }}"
                            style="{{ isset($struct['width']) ? 'width: ' . $struct['width'] . '; ' : '' }}">
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

<div class="card card-custom">
    <div class="card-body">
        @section('dataFilters')
            <table id="dataFilters" class="width-full">
                <tbody>
                    <tr>
                        <td class="valign-top td-filter-reset width-120px pb-2">
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
                            <input type="hidden" class="form-control filter-control" data-post="ids"
                                value="{{ request()->get('ids') }}">
                        @section('filters')
                            {!! $filters ?? '' !!}
                        @show
                    </td>
                    <td class="td-btn-create text-nowrap text-right">
                        @yield('buttons-before')
                        {{-- @section('buttons')
								@include('layouts.forms.btnAdd')
							@show --}}
                        @yield('buttons-after')
                    </td>
                </tr>
            </tbody>
        </table>
    @show
    <div class="table-responsive">
        @if (isset($tableStruct['datatable_1']))
            <table id="datatable_1" class="table-bordered table-hover is-datatable table" style="width: 100%;"
                data-url="{{ isset($tableStruct['url']) ? $tableStruct['url'] : rut($routes . '.grid') }}"
                data-paging="{{ $paging ?? true }}" data-info="{{ $info ?? true }}">
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
                    @yield('tableBody')
                </tbody>
            </table>
        @endif
    </div>
    @yield('card-bottom-table')
</div>
</div>

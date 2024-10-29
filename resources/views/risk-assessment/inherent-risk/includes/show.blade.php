{{-- Urutan Pelaksanaan --}}
<div class="row mb-3">
    <!-- left datatable-urutan-pelaksanaan -->
    <div class="col-sm-12">
        <div class="card card-custom">
            <div class="card-header">
                <h3 class="card-title">@yield('card-title', 'Detail Risk Register')</h3>
            </div>
            <div class="card-body row">
                <div class="col-sm-12">
                    <div class="table-responsive">
                        @if(isset($tableStruct['datatable_1']))
                        <table id="datatable_2" class="table table-bordered is-datatable" style="width: 100%;"
                            data-url="{{ $tableStruct['url'] }}" data-paging="{{ $paging ?? true }}"
                            data-info="{{ $info ?? true }}">
                            <thead>
                                <tr>
                                    @foreach ($tableStruct['datatable_1'] as $struct)
                                    @if(request()->route()->getName() == $routes.'.detail.show')
                                    @if($struct['name'] != 'action' )
                                    <th class="text-center v-middle" data-columns-name="{{ $struct['name'] ?? '' }}"
                                        data-columns-data="{{ $struct['data'] ?? '' }}"
                                        data-columns-label="{{ $struct['label'] ?? '' }}"
                                        data-columns-sortable="{{ $struct['sortable'] === true ? 'true' : 'false' }}"
                                        data-columns-width="{{ $struct['width'] ?? '' }}"
                                        data-columns-class-name="{{ $struct['className'] ?? '' }}"
                                        style="{{ isset($struct['width']) ? 'width: '.$struct['width'].'; ' : '' }}">
                                        {{ $struct['label'] }}
                                    </th>
                                    @endif
                                    @else
                                    <th class="text-center v-middle" data-columns-name="{{ $struct['name'] ?? '' }}"
                                        data-columns-data="{{ $struct['data'] ?? '' }}"
                                        data-columns-label="{{ $struct['label'] ?? '' }}"
                                        data-columns-sortable="{{ $struct['sortable'] === true ? 'true' : 'false' }}"
                                        data-columns-width="{{ $struct['width'] ?? '' }}"
                                        data-columns-class-name="{{ $struct['className'] ?? '' }}"
                                        style="{{ isset($struct['width']) ? 'width: '.$struct['width'].'; ' : '' }}">
                                        {{ $struct['label'] }}
                                    </th>
                                    @endif
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
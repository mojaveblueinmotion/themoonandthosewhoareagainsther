<div class="row">
    <div class="col-4">
        <div class="form-group row">
            <label class="col-md-6 col-form-label">{{ __('Periode') }}</label>
            <div class="col-md-6 parent-group">
                <input type="text" disabled name="periode" class="form-control base-plugin--datepicker-2 periode"
                    placeholder="{{ __('Periode') }}" value="{{ $record->riskRegister->periode->format('Y') }}">
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group row">
            <label class="col-md-4 col-form-label">{{ __('Jenis Audit') }}</label>
            <div class="col-md-8 parent-group">
                <input type="text" disabled class="form-control"  value="{{ $record->riskRegister->type->name }}">
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="form-group row">
            <label class="col-md-4 col-form-label">{{ __('Subject Audit') }}</label>
            <div class="col-md-8 parent-group">
                <input type="text" disabled class="form-control"  value="{{ $record->riskRegister->subject->name }}">
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">{{ __('Auditee') }}</label>
            <div class="col-sm-10 parent-group">
                <select class="form-control base-plugin--select2-ajax unitKerja" id="unitKerja"
                    data-placeholder="{{ __('Pilih Salah Satu') }}" disabled multiple>
                    @if ($subject = $record->riskRegister->subject)
                        @foreach($subject->details as $detail)
                        <option value="{{ $detail->id }}" selected>{{ $detail->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="table-responsive">
        @if (isset($tableStruct['datatable_2']))
            <table id="datatable_2" class="table-bordered is-datatable table" style="width: 100%;"
                data-url="{{ $tableStruct['url_2'] }}" data-paging="{{ $paging ?? true }}"
                data-info="{{ $info ?? true }}">
                <thead>
                    <tr>
                        @foreach ($tableStruct['datatable_2'] as $struct)
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
</div>
<br>
<div class="row">
    <div class="col-md-6">
        <div class="form-group row">
            <label class="col-md-4 col-form-label">
                {{ __('Prosentase') }}
                {{-- {{ $record->getTable() }} --}}
            </label>
            <div class="col-md-8 parent-group">
                <div class="input-group">
                    <input name="prosentase" class="form-control angka--persen" disabled
                        placeholder="{{ __('Prosentase') }}" value="{{ $record->riskRegister->residualRisk->prosentase }}">
                    <div class="input-group-prepend">
                        <div class="input-group-text">%</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-4 col-form-label">{{ __('Level Kemungkinan') }}</label>
            <div class="col-md-8 parent-group">
                <select class="form-control base-plugin--select2-ajax"
                    data-url="{{ rut('ajax.selectLevelKemungkinan', 'all') }}" disabled
                    placeholder="{{ __('Pilih Salah Satu') }}">
                    <option value="">{{ __('Pilih Salah Satu') }}</option>
                    @if (!empty($record->riskRegister->residualRisk->levelKemungkinan))
                        <option value="{{ $record->riskRegister->residualRisk->levelKemungkinan->id }}" selected>
                            {{ $record->riskRegister->residualRisk->levelKemungkinan->name }}</option>
                    @endif
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row">
            <label class="col-md-4 col-form-label">{{ __('Level Dampak') }}</label>
            <div class="col-md-8 parent-group">
                <select class="form-control base-plugin--select2-ajax"
                    data-url="{{ rut('ajax.selectLevelDampak', 'all') }}" disabled
                    placeholder="{{ __('Pilih Salah Satu') }}">
                    <option value="">{{ __('Pilih Salah Satu') }}</option>
                    @if (!empty($record->riskRegister->residualRisk->levelDampak))
                        <option value="{{ $record->riskRegister->residualRisk->levelDampak->id }}" selected>
                            {{ $record->riskRegister->residualRisk->levelDampak->name }}</option>
                    @endif
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-4 col-form-label">{{ __('Tingkat Resiko') }}</label>
            <div class="col-md-8 parent-group">
                <select class="form-control base-plugin--select2-ajax"
                    data-url="{{ rut('ajax.selectRiskRating') }}" disabled
                    placeholder="{{ __('Pilih Salah Satu') }}">
                    <option value="">{{ __('Pilih Salah Satu') }}</option>
                    @if (!empty($record->riskRegister->residualRisk->tingkatResiko))
                        <option value="{{ $record->riskRegister->residualRisk->tingkatResiko->id }}" selected>
                            {{ $record->riskRegister->residualRisk->tingkatResiko->name }}</option>
                    @endif
                </select>
            </div>
        </div>
    </div>
</div>
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

<div class="row">
    <div class="col-sm-6">
        <div class="form-group row">
            <label class="col-sm-4 col-form-label">{{ __('Periode') }}</label>
            <div class="col-sm-8 parent-group">
                <input type="text" disabled name="periode" class="form-control base-plugin--datepicker-2 periode"
                    placeholder="{{ __('Periode') }}" value="{{ $record->riskRegister->periode->format('Y') }}">
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group row">
            <label class="col-sm-4 col-form-label">{{ __('Jenis Audit') }}</label>
            <div class="col-sm-8 parent-group">
                <input type="text" disabled class="form-control" value="{{ $record->riskRegister->type->name }}">
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">{{ __('Subject Audit') }}</label>
            <div class="col-sm-10 parent-group">
                <input type="text" disabled class="form-control" value="{{ $record->riskRegister->subject->name }}">
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-md-2 col-form-label">{{ __('Department Auditee') }}</label>
            <div class="col-md-10 parent-group">
                <div>
                    <ol>
                        @foreach ($record->riskRegister->departmentAuditee->departments as $val)
                            <li>{{ $val->name }}</li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-md-2 col-form-label">{{ __('Main Process') }}</label>
            <div class="col-md-10 parent-group">
                <select disabled required name="main_process_id" class="form-control base-plugin--select2-ajax"
                    data-url="{{ rut('ajax.selectMainProcess', 'all') }}" placeholder="{{ __('Pilih Salah Satu') }}">
                    <option value="">{{ __('Pilih Salah Satu') }}</option>
                    @if (!empty($record->riskRegisterDetail->kodeResiko))
                        <option value="{{ $record->riskRegisterDetail->kodeResiko->id }}" selected>
                            {{ $record->riskRegisterDetail->kodeResiko->name }}</option>
                    @endif
                </select>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-md-2 col-form-label">{{ __('Sub Process') }}</label>
            <div class="col-sm-10 input-group parent-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">
                        {{ $record->riskRegisterDetail->id_resiko }}
                    </span>
                </div>
                <input type="text" disabled class="form-control"
                    value="{{ $record->riskRegisterDetail->jenisResiko->name }}">
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group row">
            <label class="col-md-2 col-form-label">{{ __('Proses Objective') }}</label>
            <div class="col-md-10 parent-group">
                <textarea disabled name="objective" class="base-plugin--summernote" placeholder="{{ __('Proses Objective') }}">{{ $record->riskRegisterDetail->objective }}</textarea>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-2 col-form-label">{{ __('Risk Event') }}</label>
            <div class="col-md-10 parent-group">
                <textarea disabled name="peristiwa" class="base-plugin--summernote" placeholder="{{ __('Risk Event') }}">{{ $record->riskRegisterDetail->peristiwa }}</textarea>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-2 col-form-label">{{ __('Risk Cause') }}</label>
            <div class="col-md-10 parent-group">
                <textarea disabled name="penyebab" class="base-plugin--summernote" placeholder="{{ __('Risk Cause') }}">{{ $record->riskRegisterDetail->penyebab }}</textarea>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-2 col-form-label">{{ __('Risk Impact') }}</label>
            <div class="col-md-10 parent-group">
                <textarea disabled name="dampak" class="base-plugin--summernote" placeholder="{{ __('Risk Impact') }}">{{ $record->riskRegisterDetail->dampak }}</textarea>
            </div>
        </div>
    </div>
</div>
{{-- <div class="row">
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
</div> --}}

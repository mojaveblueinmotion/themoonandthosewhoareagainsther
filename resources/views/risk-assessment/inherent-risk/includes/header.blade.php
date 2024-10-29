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
            <label class="col-sm-2 col-form-label">{{ __('Main Process') }}</label>
            <div class="col-sm-10 parent-group">
                <input type="text" disabled class="form-control"
                    value="{{ $record->riskRegisterDetail->kodeResiko->name }}">
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">{{ __('Sub Process') }}</label>
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
    <div class="col-12">
        <div class="form-group row">
            <label class="col-2 col-form-label">{{ __('Proses Objective') }}</label>
            <div class="col-10 parent-group">
                <textarea required disabled name="objective" class="base-plugin--summernote"
                    placeholder="{{ __('Proses Objective') }}">{!! $record->riskRegisterDetail->objective !!}</textarea>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="separator separator-dashed my-5"></div>

        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="objective-tab" data-toggle="tab" data-target="#objective"
                    type="button" role="tab" aria-controls="objective"
                    aria-selected="true">{{ __('Proses Objective') }}</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="condition-tab" data-toggle="tab" data-target="#condition"
                    type="button" role="tab" aria-controls="condition"
                    aria-selected="true">{{ __('Risk Event') }}</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="criteria-tab" data-toggle="tab" data-target="#criteria" type="button"
                    role="tab" aria-controls="criteria" aria-selected="true">{{ __('Risk Cause') }}</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="because-tab" data-toggle="tab" data-target="#because" type="button"
                    role="tab" aria-controls="because" aria-selected="false">{{ __('Risk Impact') }}</button>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="objective" role="tabpanel" aria-labelledby="objective-tab">
                <div class="form-group row">
                    <div class="col-md-12 parent-group">
                        <textarea disabled name="objective" class="base-plugin--summernote" placeholder="{{ __('Objective') }}">{{ $record->riskRegisterDetail->objective }}</textarea>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade " id="condition" role="tabpanel" aria-labelledby="condition-tab">
                <div class="form-group row">
                    <div class="col-md-12 parent-group">
                        <textarea disabled name="peristiwa" class="base-plugin--summernote" placeholder="{{ __('Risk Event') }}">{{ $record->riskRegisterDetail->peristiwa }}</textarea>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="criteria" role="tabpanel" aria-labelledby="criteria-tab">
                <div class="form-group row">
                    <div class="col-md-12 parent-group">
                        <textarea disabled name="penyebab" class="base-plugin--summernote" placeholder="{{ __('Risk Cause') }}">{{ $record->riskRegisterDetail->penyebab }}</textarea>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="because" role="tabpanel" aria-labelledby="because-tab">
                <div class="form-group row">
                    <div class="col-md-12 parent-group">
                        <textarea disabled name="dampak" class="base-plugin--summernote" placeholder="{{ __('Risk Impact') }}">{{ $record->riskRegisterDetail->dampak }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

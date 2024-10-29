{{-- {{ dd($record->riskRegister->id, $record->risk_assessment_register_id) }} --}}
@extends('layouts.page')

@section('page')
    @csrf
    @method('PATCH')
    {{-- <input type="hidden" name="formTab" value="{{ $tab }}"> --}}
    <input type="hidden" id="riskRegisterIdCtrl" value="{{ $record->risk_assessment_register_id }}">
    <div class="row">
        <div class="col-md-3">
            @include($views . '.includes.tab-create-aside')
        </div>
        <div class="col-md-9">
            <div class="card card-custom" style="height:100%;">
                <div class="card-header py-3">
                    <div class="card-title">
                        <h3 class="card-label font-weight-bolder text-dark" id="CtrlCardLabel">1. Risk Register</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="first_tab" role="tabpanel" aria-labelledby="first_tab">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label">
                                            {{ __('Periode') }}
                                            {{-- {{ $record->getTable() }} --}}
                                        </label>
                                        <div class="col-md-8 parent-group">
                                            <input type="text" disabled name="periode"
                                                class="form-control base-plugin--datepicker-2 periode"
                                                placeholder="{{ __('Periode') }}"
                                                value="{{ $record->riskRegister->periode->format('Y') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label">{{ __('Jenis Audit') }}</label>
                                        <div class="col-md-8 parent-group">
                                            <select name="type_id" class="form-control base-plugin--select2-ajax type_id"
                                                data-url="{{ rut('ajax.selectTypeAudit', ['search' => 'without_investigasi']) }}"
                                                placeholder="{{ __('Pilih Salah Satu') }}" disabled>
                                                <option value="">{{ __('Pilih Salah Satu') }}</option>
                                                @if ($record->riskRegister)
                                                    <option value="{{ $record->riskRegister->type_id }}" selected>
                                                        {{ $record->riskRegister->type->name }}</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">{{ __('Subject Audit') }}</label>
                                        <div class="col-md-10 parent-group">
                                            <select id="unitKerjaCtrl" disabled name="unit_kerja_id"
                                                class="form-control base-plugin--select2-ajax"
                                                data-url="{{ rut('ajax.selectStruct', 'all') }}"
                                                placeholder="{{ __('Pilih Salah Satu') }}">
                                                <option value="">{{ __('Pilih Salah Satu') }}</option>
                                                @if (!empty($record->riskRegister->subject))
                                                    <option value="{{ $record->riskRegister->subject->id }}" selected>
                                                        {{ $record->riskRegister->subject->name }}</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">{{ __('Dept. Auditee') }}</label>
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
                            {{-- <div class="form-group row">
                                    <label class="col-md-2 col-form-label">{{ __('Sasaran') }}</label>
                                    <div class="col-md-10 parent-group">
                                        <textarea disabled name="sasaran" class="base-plugin--summernote" placeholder="{{ __('Sasaran') }}">{{ $record->riskRegister->sasaran }}</textarea>
                                    </div>
                                </div> --}}
                            <div class="row">
                                <div class="table-responsive">
                                    @if (isset($tableStruct['datatable_2']))
                                        <table id="datatable_2" class="table-bordered is-datatable table"
                                            style="width: 100%;" data-url="{{ $tableStruct['url_2'] }}"
                                            data-paging="{{ $paging ?? true }}" data-info="{{ $info ?? true }}">
                                            <thead>
                                                <tr>
                                                    @foreach ($tableStruct['datatable_2'] as $struct)
                                                        <th class="v-middle text-center"
                                                            data-columns-name="{{ $struct['name'] ?? '' }}"
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
                        </div>
                        <div class="tab-pane fade" id="second_tab" role="tabpanel" aria-labelledby="second_tab">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label">
                                            {{ __('Periode') }}
                                            {{-- {{ $record->getTable() }} --}}
                                        </label>
                                        <div class="col-md-8 parent-group">
                                            <input type="text" disabled name="periode"
                                                class="form-control base-plugin--datepicker-2 periode"
                                                placeholder="{{ __('Periode') }}"
                                                value="{{ $record->riskRegister->periode->format('Y') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label">{{ __('Jenis Audit') }}</label>
                                        <div class="col-md-8 parent-group">
                                            <select name="type_id" class="form-control base-plugin--select2-ajax type_id"
                                                data-url="{{ rut('ajax.selectTypeAudit', ['search' => 'without_investigasi']) }}"
                                                placeholder="{{ __('Pilih Salah Satu') }}" disabled>
                                                <option value="">{{ __('Pilih Salah Satu') }}</option>
                                                @if ($record->riskRegister)
                                                    <option value="{{ $record->riskRegister->type_id }}" selected>
                                                        {{ $record->riskRegister->type->name }}</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">{{ __('Subject Audit') }}</label>
                                        <div class="col-md-10 parent-group">
                                            <select id="unitKerjaCtrl" disabled name="unit_kerja_id"
                                                class="form-control base-plugin--select2-ajax"
                                                data-url="{{ rut('ajax.selectStruct', 'all') }}"
                                                placeholder="{{ __('Pilih Salah Satu') }}">
                                                <option value="">{{ __('Pilih Salah Satu') }}</option>
                                                @if (!empty($record->riskRegister->subject))
                                                    <option value="{{ $record->riskRegister->subject->id }}" selected>
                                                        {{ $record->riskRegister->subject->name }}</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">{{ __('Dept. Auditee') }}</label>
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

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">{{ __('Main Process') }}</label>
                                <div class="col-md-10 parent-group">
                                    <select id="kodeResikoCtrl" name="kode_resiko"
                                        class="form-control base-plugin--select2-ajax"
                                        placeholder="{{ __('Pilih Salah Satu') }}">
                                        <option value="">{{ __('Pilih Salah Satu') }}</option>
                                        @foreach ($record->riskRegister->details->unique('main_process_id') as $detailRisk)
                                            <option value="{{ $detailRisk->main_process_id }}">
                                                {{ $detailRisk->kodeResiko->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">{{ __('Sub Process') }}</label>
                                <div class="col-md-10 parent-group">
                                    <select name="sub_process_id" id="subProcessCtrl"
                                        class="form-control base-plugin--select2-ajax sub_process_id_detail"
                                        data-url="{{ route('ajax.selectAspect', ['search' => 'by_risk_register', 'main_process_id' => '']) }}"
                                        data-url-origin="{{ rut('ajax.selectAspect', ['search' => 'by_risk_register']) }}"
                                        disabled placeholder="{{ __('Pilih Salah Satu') }}">
                                        <option value="">{{ __('Pilih Salah Satu') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                {{-- <div class="col-sm-12">
										<div class="form-group row">
											<label class="col-sm-2 col-form-label">{{ __('Main Process') }}</label>
											<div class="col-sm-10 parent-group">
												<input type="text" disabled class="form-control"  value="{{ $record->kodeResiko->name }}">
											</div>
										</div>
									</div>
									<div class="col-sm-12">
										<div class="form-group row">
											<label class="col-sm-2 col-form-label">{{ __('Sub Process') }}</label>
											<div class="col-sm-10 parent-group">
												<input type="text" disabled class="form-control"  value="{{ $record->jenisResiko->name }}">
											</div>
										</div>
									</div> --}}
                                <div class="col-md-12">
                                    <div class="separator separator-dashed my-5"></div>

                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="objective-tab" data-toggle="tab"
                                                data-target="#objek" type="button" role="tab"
                                                aria-controls="objek"
                                                aria-selected="true">{{ __('Proses Objective') }}</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="condition-tab" data-toggle="tab"
                                                data-target="#condition" type="button" role="tab"
                                                aria-controls="condition"
                                                aria-selected="true">{{ __('Risk Event') }}</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="criteria-tab" data-toggle="tab"
                                                data-target="#criteria" type="button" role="tab"
                                                aria-controls="criteria"
                                                aria-selected="true">{{ __('Risk Cause') }}</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="because-tab" data-toggle="tab"
                                                data-target="#because" type="button" role="tab"
                                                aria-controls="because"
                                                aria-selected="false">{{ __('Risk Impact') }}</button>
                                        </li>
                                    </ul>

                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade active show" id="objek" role="tabpanel"
                                        aria-labelledby="objective-tab">
                                        <div class="form-group row">
                                            <div class="col-md-12 parent-group">
                                                <textarea disabled name="objective" id="objective" class="form-control"
                                                    placeholder="{{ __('Objective') }}"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                        <div class="tab-pane fade" id="condition" role="tabpanel"
                                            aria-labelledby="condition-tab">
                                            <div class="form-group row">
                                                <div class="col-md-12 parent-group">
                                                    <textarea disabled name="peristiwa" id="peristiwa" class="form-control"
                                                        placeholder="{{ __('Risk Event') }}"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="criteria" role="tabpanel"
                                            aria-labelledby="criteria-tab">
                                            <div class="form-group row">
                                                <div class="col-md-12 parent-group">
                                                    <textarea disabled name="penyebab" id="penyebab" class="form-control"
                                                        placeholder="{{ __('Risk Cause') }}"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="because" role="tabpanel"
                                            aria-labelledby="because-tab">
                                            <div class="form-group row">
                                                <div class="col-md-12 parent-group">
                                                    <textarea disabled name="dampak" id="dampak" class="form-control"
                                                        placeholder="{{ __('Risk Impact') }}"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="form-group row">
                                    <label class="col-md-2 col-form-label">{{ __('Sasaran') }}</label>
                                    <div class="col-md-10 parent-group">
                                        <textarea disabled name="sasaran" class="form-control" placeholder="{{ __('Sasaran') }}">{{ $record->riskRegister->sasaran }}</textarea>
                                    </div>
                                </div> --}}
                            <hr>
                            <div class="container-fluid mt-5">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card card-custom">
                                            <div class="card-header">
                                                <h3 class="card-title">Likelihood</h3>
                                                <div class="card-toolbar">
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label
                                                                class="col-md-6 col-form-label">{{ __('Complexity (30%)') }}</label>
                                                            <div class="col-md-6 parent-group">
                                                                <input type="text" id="complexity" disabled
                                                                    name="complexity" class="form-control"
                                                                    placeholder="{{ __('Complexity (30%)') }}"
                                                                    value="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label
                                                                class="col-md-6 col-form-label">{{ __('Volume (35%)') }}</label>
                                                            <div class="col-md-6 parent-group">
                                                                <input type="text" id="volume" disabled
                                                                    name="volume" class="form-control"
                                                                    placeholder="{{ __('Volume (35%)') }}"
                                                                    value="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label
                                                                class="col-md-6 col-form-label">{{ __('Known Issue (20%)') }}</label>
                                                            <div class="col-md-6 parent-group">
                                                                <input type="text" id="known_issue" disabled
                                                                    name="known_issue" class="form-control"
                                                                    placeholder="{{ __('Known Issue (20%)') }}"
                                                                    value="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label
                                                                class="col-md-6 col-form-label">{{ __('Changing Process & People (15%)') }}</label>
                                                            <div class="col-md-6 parent-group">
                                                                <input type="text" id="changing_process" disabled
                                                                    name="changing_process" class="form-control"
                                                                    placeholder="{{ __('Changing Process & People (15%)') }}"
                                                                    value="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label
                                                                class="col-md-6 col-form-label">{{ __('Total Score') }}</label>
                                                            <div class="col-md-6 parent-group">
                                                                <input type="text" class="form-control" value=""
                                                                    id="total_score_likelihood" disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="container-fluid mt-5">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card card-custom">
                                            <div class="card-header">
                                                <h3 class="card-title">Impact</h3>
                                                <div class="card-toolbar">
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label
                                                                class="col-md-6 col-form-label">{{ __('Materiality (40%)') }}</label>
                                                            <div class="col-md-6 parent-group">
                                                                <input type="text" id="materiality" disabled
                                                                    name="materiality" class="form-control"
                                                                    placeholder="{{ __('Materiality (40%)') }}"
                                                                    value="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label
                                                                class="col-md-6 col-form-label">{{ __('Legal & Compliance (30%)') }}</label>
                                                            <div class="col-md-6 parent-group">
                                                                <input type="text" id="legal" disabled
                                                                    name="legal" class="form-control"
                                                                    placeholder="{{ __('Legal & Compliance (30%)') }}"
                                                                    value="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label
                                                                class="col-md-6 col-form-label">{{ __('Operational (30%)') }}</label>
                                                            <div class="col-md-6 parent-group">
                                                                <input type="text" id="operational" disabled
                                                                    name="operational" class="form-control"
                                                                    placeholder="{{ __('Operational (30%)') }}"
                                                                    value="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label
                                                                class="col-md-6 col-form-label">{{ __('Total Score') }}</label>
                                                            <div class="col-md-6 parent-group">
                                                                <input type="text" class="form-control" value=""
                                                                    id="total_score_impact" disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="third_tab" role="tabpanel" aria-labelledby="third_tab">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label">
                                            {{ __('Periode') }}
                                            {{-- {{ $record->getTable() }} --}}
                                        </label>
                                        <div class="col-md-8 parent-group">
                                            <input type="text" disabled name="periode"
                                                class="form-control base-plugin--datepicker-2 periode"
                                                placeholder="{{ __('Periode') }}"
                                                value="{{ $record->riskRegister->periode->format('Y') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label">{{ __('Jenis Audit') }}</label>
                                        <div class="col-md-8 parent-group">
                                            <select name="type_id" class="form-control base-plugin--select2-ajax type_id"
                                                data-url="{{ rut('ajax.selectTypeAudit', ['search' => 'without_investigasi']) }}"
                                                placeholder="{{ __('Pilih Salah Satu') }}" disabled>
                                                <option value="">{{ __('Pilih Salah Satu') }}</option>
                                                @if ($record->riskRegister)
                                                    <option value="{{ $record->riskRegister->type_id }}" selected>
                                                        {{ $record->riskRegister->type->name }}</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">{{ __('Subject Audit') }}</label>
                                        <div class="col-md-10 parent-group">
                                            <select id="unitKerjaCtrl" disabled name="unit_kerja_id"
                                                class="form-control base-plugin--select2-ajax"
                                                data-url="{{ rut('ajax.selectStruct', 'all') }}"
                                                placeholder="{{ __('Pilih Salah Satu') }}">
                                                <option value="">{{ __('Pilih Salah Satu') }}</option>
                                                @if (!empty($record->riskRegister->subject))
                                                    <option value="{{ $record->riskRegister->subject->id }}" selected>
                                                        {{ $record->riskRegister->subject->name }}</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">{{ __('Dept. Auditee') }}</label>
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

                            <table id="dataFilters" class="width-full">
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="form-group row">
                                                <label class="col-md-2 col-form-label">{{ __('Main Process') }}</label>
                                                <div class="col-md-10 parent-group">
                                                    <select id="kodeResikoCtrlCurrent" name="kode_resiko"
                                                        class="form-control filter-control base-plugin--select2-ajax"
                                                        data-post="kode_resiko"
                                                        placeholder="{{ __('Pilih Salah Satu') }}">
                                                        <option value="">{{ __('Pilih Salah Satu') }}</option>
                                                        @foreach ($record->riskRegister->details->unique('main_process_id') as $detailRisk)
                                                            <option value="{{ $detailRisk->main_process_id }}">
                                                                {{ $detailRisk->kodeResiko->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">{{ __('Sub Process') }}</label>
                                <div class="col-md-10 parent-group">
                                    <select name="sub_process_id" id="subProcessCtrlCurrent"
                                        class="form-control base-plugin--select2-ajax sub_process_id_detail_current"
                                        data-url="{{ route('ajax.selectAspect', ['search' => 'by_risk_register', 'main_process_id' => '']) }}"
                                        data-url-origin="{{ rut('ajax.selectAspect', ['search' => 'by_risk_register']) }}"
                                        disabled placeholder="{{ __('Pilih Salah Satu') }}">
                                        <option value="">{{ __('Pilih Salah Satu') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                {{-- <div class="col-sm-12">
										<div class="form-group row">
											<label class="col-sm-2 col-form-label">{{ __('Main Process') }}</label>
											<div class="col-sm-10 parent-group">
												<input type="text" disabled class="form-control"  value="{{ $record->kodeResiko->name }}">
											</div>
										</div>
									</div>
									<div class="col-sm-12">
										<div class="form-group row">
											<label class="col-sm-2 col-form-label">{{ __('Sub Process') }}</label>
											<div class="col-sm-10 parent-group">
												<input type="text" disabled class="form-control"  value="{{ $record->jenisResiko->name }}">
											</div>
										</div>
									</div> --}}
                                    <div class="col-md-12">
                                        <div class="separator separator-dashed my-5"></div>

                                        <ul class="nav nav-tabs" id="myTab2" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link active" id="objective-tab_2" data-toggle="tab"
                                                    data-target="#object_2" type="button" role="tab"
                                                    aria-controls="object_2"
                                                    aria-selected="true">{{ __('Proses Objective') }}</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="condition-tab_2" data-toggle="tab"
                                                    data-target="#condition_2" type="button" role="tab"
                                                    aria-controls="condition_2"
                                                    aria-selected="true">{{ __('Risk Event') }}</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="criteria-tab_2" data-toggle="tab"
                                                    data-target="#criteria_2" type="button" role="tab"
                                                    aria-controls="criteria_2"
                                                    aria-selected="true">{{ __('Risk Cause') }}</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="because-tab_2" data-toggle="tab"
                                                    data-target="#because_2" type="button" role="tab"
                                                    aria-controls="because_2"
                                                    aria-selected="false">{{ __('Risk Impact') }}</button>
                                            </li>
                                        </ul>

                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade show active" id="object_2" role="tabpanel"
                                                aria-labelledby="objective-tab_2">
                                                <div class="form-group row">
                                                    <div class="col-md-12 parent-group">
                                                        <textarea disabled name="objective" id="objective_2" class="form-control"
                                                        placeholder="{{ __('Objective') }}"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="condition_2" role="tabpanel"
                                                aria-labelledby="condition-tab_2">
                                                <div class="form-group row">
                                                    <div class="col-md-12 parent-group">
                                                        <textarea disabled name="peristiwa" id="peristiwa_2" class="form-control"
                                                            placeholder="{{ __('Risk Event') }}"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="criteria_2" role="tabpanel"
                                                aria-labelledby="criteria-tab_2">
                                                <div class="form-group row">
                                                    <div class="col-md-12 parent-group">
                                                        <textarea disabled name="penyebab" id="penyebab_2" class="form-control"
                                                            placeholder="{{ __('Risk Cause') }}"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="because_2" role="tabpanel"
                                                aria-labelledby="because-tab_2">
                                                <div class="form-group row">
                                                    <div class="col-md-12 parent-group">
                                                        <textarea disabled name="dampak" id="dampak_2" class="form-control"
                                                            placeholder="{{ __('Risk Impact') }}"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            {{-- <div class="form-group row">
                                    <label class="col-md-2 col-form-label">{{ __('Sasaran') }}</label>
                                    <div class="col-md-10 parent-group">
                                        <textarea disabled name="sasaran" class="base-plugin--summernote" placeholder="{{ __('Sasaran') }}">{{ $record->riskRegister->sasaran }}</textarea>
                                    </div>
                                </div> --}}

                            <div class="container-fluid mt-5">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card card-custom">
                                            <div class="card-header">
                                                <h3 class="card-title">Likelihood</h3>
                                                <div class="card-toolbar">
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label
                                                                class="col-md-6 col-form-label">{{ __('Complexity (30%)') }}</label>
                                                            <div class="col-md-6 parent-group">
                                                                <input type="text" id="complexity_2" disabled
                                                                    name="complexity_2" class="form-control"
                                                                    placeholder="{{ __('Complexity (30%)') }}"
                                                                    value="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label
                                                                class="col-md-6 col-form-label">{{ __('Volume (35%)') }}</label>
                                                            <div class="col-md-6 parent-group">
                                                                <input type="text" id="volume_2" disabled
                                                                    name="volume_2" class="form-control"
                                                                    placeholder="{{ __('Volume (35%)') }}"
                                                                    value="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label
                                                                class="col-md-6 col-form-label">{{ __('Known Issue (20%)') }}</label>
                                                            <div class="col-md-6 parent-group">
                                                                <input type="text" id="known_issue_2" disabled
                                                                    name="known_issue_2" class="form-control"
                                                                    placeholder="{{ __('Known Issue (20%)') }}"
                                                                    value="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label
                                                                class="col-md-6 col-form-label">{{ __('Changing Process & People (15%)') }}</label>
                                                            <div class="col-md-6 parent-group">
                                                                <input type="text" id="changing_process_2" disabled
                                                                    name="changing_process_2" class="form-control"
                                                                    placeholder="{{ __('Changing Process & People (15%)') }}"
                                                                    value="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label
                                                                class="col-md-6 col-form-label">{{ __('Total Score') }}</label>
                                                            <div class="col-md-6 parent-group">
                                                                <input type="text" class="form-control" value=""
                                                                    id="total_score_likelihood_2" disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="container-fluid mt-5">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card card-custom">
                                            <div class="card-header">
                                                <h3 class="card-title">Impact</h3>
                                                <div class="card-toolbar">
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label
                                                                class="col-md-6 col-form-label">{{ __('Materiality (40%)') }}</label>
                                                            <div class="col-md-6 parent-group">
                                                                <input type="text" id="materiality_2" disabled
                                                                    name="materiality_2" class="form-control"
                                                                    placeholder="{{ __('Materiality (40%)') }}"
                                                                    value="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label
                                                                class="col-md-6 col-form-label">{{ __('Legal & Compliance (30%)') }}</label>
                                                            <div class="col-md-6 parent-group">
                                                                <input type="text" id="legal_2" disabled
                                                                    name="legal_2" class="form-control"
                                                                    placeholder="{{ __('Legal & Compliance (30%)') }}"
                                                                    value="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label
                                                                class="col-md-6 col-form-label">{{ __('Operational (30%)') }}</label>
                                                            <div class="col-md-6 parent-group">
                                                                <input type="text" id="operational_2" disabled
                                                                    name="operational_2" class="form-control"
                                                                    placeholder="{{ __('Operational (30%)') }}"
                                                                    value="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label
                                                                class="col-md-6 col-form-label">{{ __('Total Score') }}</label>
                                                            <div class="col-md-6 parent-group">
                                                                <input type="text" class="form-control" value=""
                                                                    id="total_score_impact_2" disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="row">
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
								</div> --}}
                        </div>
                        {{-- <div class="tab-pane fade" id="fourth_tab" role="tabpanel" aria-labelledby="fourth_tab">
								<div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-md-2 col-form-label">
                                                {{ __('Risk Rating') }}
                                            </label>
                                            <div class="col-md-10 parent-group">
                                                <select disabled name="risk_rating_id"
                                                    class="form-control base-plugin--select2-ajax"
                                                    data-url="{{ rut('ajax.selectRiskRating', 'all') }}"
                                                    placeholder="{{ __('Pilih Salah Satu') }}">
                                                    <option value="">{{ __('Pilih Salah Satu') }}</option>
                                                    @if (!empty($record->riskRating))
                                                        <option value="{{ $record->riskRating->id }}" selected>
                                                            {{ $record->riskRating->name }}</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
							</div> --}}
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        @include('layouts.forms.btnBack')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include($views . '.includes.scripts')
    <script>
        $(document).ready(function() {
            $('.nav-tabs').on('shown.bs.tab', function(event) {
                const activeTabText = $(event.target).find('.nav-text').text();
                console.log(activeTabText);

                const cardLabel = $('#CtrlCardLabel');
                if (cardLabel.length) {
                    cardLabel.text(activeTabText);
                }
            });
        });
    </script>
@endpush

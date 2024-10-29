{{-- {{ dd(123) }} --}}
@extends('layouts.page')

@section('page')
    @csrf
    @method('PATCH')
    {{-- <input type="hidden" name="formTab" value="{{ $tab }}"> --}}
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

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">{{ __('Main Process') }}</label>
                                        <div class="col-sm-10 parent-group">
                                            <input type="text" disabled class="form-control"
                                                value="{{ $record->kodeResiko->name }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">{{ __('Sub Process') }}</label>
                                        <div class="col-sm-10 input-group parent-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">
                                                    {{ $record->id_resiko }}
                                                </span>
                                            </div>
                                            <input type="text" disabled class="form-control"
                                                value="{{ $record->jenisResiko->name }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-2 col-form-label">{{ __('Proses Objective') }}</label>
                                        <div class="col-10 parent-group">
                                            <textarea required disabled name="objective" class="base-plugin--summernote"
                                                placeholder="{{ __('Proses Objective') }}">{!! $record->objective !!}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="separator separator-dashed my-5"></div>

                                    <ul class="nav nav-tabs" id="myTab_second" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="condition-tab_second" data-toggle="tab"
                                                data-target="#condition_second" type="button" role="tab"
                                                aria-controls="condition_second"
                                                aria-selected="true">{{ __('Risk Event') }}</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="criteria-tab_second" data-toggle="tab"
                                                data-target="#criteria_second" type="button" role="tab"
                                                aria-controls="criteria_second"
                                                aria-selected="true">{{ __('Risk Cause') }}</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="because-tab_second" data-toggle="tab"
                                                data-target="#because_second" type="button" role="tab"
                                                aria-controls="because_second"
                                                aria-selected="false">{{ __('Risk Impact') }}</button>
                                        </li>
                                    </ul>

                                    <div class="tab-content" id="myTabContent_second">
                                        <div class="tab-pane fade show active" id="condition_second" role="tabpanel"
                                            aria-labelledby="condition-tab_second">
                                            <div class="form-group row">
                                                <div class="col-md-12 parent-group">
                                                    <textarea disabled name="peristiwa" class="base-plugin--summernote" placeholder="{{ __('Risk Event') }}">{{ $record->peristiwa }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="criteria_second" role="tabpanel"
                                            aria-labelledby="criteria-tab_second">
                                            <div class="form-group row">
                                                <div class="col-md-12 parent-group">
                                                    <textarea disabled name="penyebab" class="base-plugin--summernote" placeholder="{{ __('Risk Cause') }}">{{ $record->penyebab }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="because_second" role="tabpanel"
                                            aria-labelledby="because-tab_second">
                                            <div class="form-group row">
                                                <div class="col-md-12 parent-group">
                                                    <textarea disabled name="dampak" class="base-plugin--summernote" placeholder="{{ __('Risk Impact') }}">{{ $record->dampak }}</textarea>
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
                            {{-- <div class="form-group row">
									<label class="col-md-2 col-form-label">{{ __('Main Process') }}</label>
									<div class="col-md-10 parent-group">
										<select id="kodeResikoCtrl" name="kode_resiko"
											class="form-control base-plugin--select2-ajax"
											placeholder="{{ __('Pilih Salah Satu') }}">
											<option value="">{{ __('Pilih Salah Satu') }}</option>
											@foreach ($record->riskRegister->details as $detailRisk)
											<option value="{{ $detailRisk->main_process_id }}">
												{{ $detailRisk->mainProcess->name }}</option>
											@endforeach
										</select>
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
                                                                <select disabled required name="complexity"
                                                                    class="form-control base-plugin--select2"
                                                                    placeholder="{{ __('Pilih Salah Satu') }}">
                                                                    <option value="">{{ __('Pilih Salah Satu') }}
                                                                    </option>
                                                                    <option
                                                                        @if ($record->inherentRisk->complexity == 1) selected @endif
                                                                        value="1">1</option>
                                                                    <option
                                                                        @if ($record->inherentRisk->complexity == 2) selected @endif
                                                                        value="2">2</option>
                                                                    <option
                                                                        @if ($record->inherentRisk->complexity == 3) selected @endif
                                                                        value="3">3</option>
                                                                    <option
                                                                        @if ($record->inherentRisk->complexity == 4) selected @endif
                                                                        value="4">4</option>
                                                                    <option
                                                                        @if ($record->inherentRisk->complexity == 5) selected @endif
                                                                        value="5">5</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label
                                                                class="col-md-6 col-form-label">{{ __('Volume (35%)') }}</label>
                                                            <div class="col-md-6 parent-group">
                                                                <select disabled required name="volume"
                                                                    class="form-control base-plugin--select2"
                                                                    placeholder="{{ __('Pilih Salah Satu') }}">
                                                                    <option value="">{{ __('Pilih Salah Satu') }}
                                                                    </option>
                                                                    <option
                                                                        @if ($record->inherentRisk->volume == 1) selected @endif
                                                                        value="1">1</option>
                                                                    <option
                                                                        @if ($record->inherentRisk->volume == 2) selected @endif
                                                                        value="2">2</option>
                                                                    <option
                                                                        @if ($record->inherentRisk->volume == 3) selected @endif
                                                                        value="3">3</option>
                                                                    <option
                                                                        @if ($record->inherentRisk->volume == 4) selected @endif
                                                                        value="4">4</option>
                                                                    <option
                                                                        @if ($record->inherentRisk->volume == 5) selected @endif
                                                                        value="5">5</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label
                                                                class="col-md-6 col-form-label">{{ __('Known Issue (20%)') }}</label>
                                                            <div class="col-md-6 parent-group">
                                                                <select disabled required name="known_issue"
                                                                    class="form-control base-plugin--select2"
                                                                    placeholder="{{ __('Pilih Salah Satu') }}">
                                                                    <option value="">{{ __('Pilih Salah Satu') }}
                                                                    </option>
                                                                    <option
                                                                        @if ($record->inherentRisk->known_issue == 1) selected @endif
                                                                        value="1">1</option>
                                                                    <option
                                                                        @if ($record->inherentRisk->known_issue == 2) selected @endif
                                                                        value="2">2</option>
                                                                    <option
                                                                        @if ($record->inherentRisk->known_issue == 3) selected @endif
                                                                        value="3">3</option>
                                                                    <option
                                                                        @if ($record->inherentRisk->known_issue == 4) selected @endif
                                                                        value="4">4</option>
                                                                    <option
                                                                        @if ($record->inherentRisk->known_issue == 5) selected @endif
                                                                        value="5">5</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label
                                                                class="col-md-6 col-form-label">{{ __('Changing Process & People (15%)') }}</label>
                                                            <div class="col-md-6 parent-group">
                                                                <select disabled required name="chaning_process"
                                                                    class="form-control base-plugin--select2"
                                                                    placeholder="{{ __('Pilih Salah Satu') }}">
                                                                    <option value="">{{ __('Pilih Salah Satu') }}
                                                                    </option>
                                                                    <option
                                                                        @if ($record->inherentRisk->chaning_process == 1) selected @endif
                                                                        value="1">1</option>
                                                                    <option
                                                                        @if ($record->inherentRisk->chaning_process == 2) selected @endif
                                                                        value="2">2</option>
                                                                    <option
                                                                        @if ($record->inherentRisk->chaning_process == 3) selected @endif
                                                                        value="3">3</option>
                                                                    <option
                                                                        @if ($record->inherentRisk->chaning_process == 4) selected @endif
                                                                        value="4">4</option>
                                                                    <option
                                                                        @if ($record->inherentRisk->chaning_process == 5) selected @endif
                                                                        value="5">5</option>
                                                                </select>
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
                                                                <input type="text" class="form-control"
                                                                    value="{{ $record->inherentRisk->total_likehood ?? '' }}"
                                                                    disabled>
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
                                                                <select disabled required name="materiality"
                                                                    class="form-control base-plugin--select2"
                                                                    placeholder="{{ __('Pilih Salah Satu') }}">
                                                                    <option value="">{{ __('Pilih Salah Satu') }}
                                                                    </option>
                                                                    <option
                                                                        @if ($record->inherentRisk->materiality == 1) selected @endif
                                                                        value="1">1</option>
                                                                    <option
                                                                        @if ($record->inherentRisk->materiality == 2) selected @endif
                                                                        value="2">2</option>
                                                                    <option
                                                                        @if ($record->inherentRisk->materiality == 3) selected @endif
                                                                        value="3">3</option>
                                                                    <option
                                                                        @if ($record->inherentRisk->materiality == 4) selected @endif
                                                                        value="4">4</option>
                                                                    <option
                                                                        @if ($record->inherentRisk->materiality == 5) selected @endif
                                                                        value="5">5</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label
                                                                class="col-md-6 col-form-label">{{ __('Legal & Compliance (30%)') }}</label>
                                                            <div class="col-md-6 parent-group">
                                                                <select disabled required name="legal"
                                                                    class="form-control base-plugin--select2"
                                                                    placeholder="{{ __('Pilih Salah Satu') }}">
                                                                    <option value="">{{ __('Pilih Salah Satu') }}
                                                                    </option>
                                                                    <option
                                                                        @if ($record->inherentRisk->legal == 1) selected @endif
                                                                        value="1">1</option>
                                                                    <option
                                                                        @if ($record->inherentRisk->legal == 2) selected @endif
                                                                        value="2">2</option>
                                                                    <option
                                                                        @if ($record->inherentRisk->legal == 3) selected @endif
                                                                        value="3">3</option>
                                                                    <option
                                                                        @if ($record->inherentRisk->legal == 4) selected @endif
                                                                        value="4">4</option>
                                                                    <option
                                                                        @if ($record->inherentRisk->legal == 5) selected @endif
                                                                        value="5">5</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label
                                                                class="col-md-6 col-form-label">{{ __('Operational (30%)') }}</label>
                                                            <div class="col-md-6 parent-group">
                                                                <select disabled required name="operational"
                                                                    class="form-control base-plugin--select2"
                                                                    placeholder="{{ __('Pilih Salah Satu') }}">
                                                                    <option value="">{{ __('Pilih Salah Satu') }}
                                                                    </option>
                                                                    <option
                                                                        @if ($record->inherentRisk->operational == 1) selected @endif
                                                                        value="1">1</option>
                                                                    <option
                                                                        @if ($record->inherentRisk->operational == 2) selected @endif
                                                                        value="2">2</option>
                                                                    <option
                                                                        @if ($record->inherentRisk->operational == 3) selected @endif
                                                                        value="3">3</option>
                                                                    <option
                                                                        @if ($record->inherentRisk->operational == 4) selected @endif
                                                                        value="4">4</option>
                                                                    <option
                                                                        @if ($record->inherentRisk->operational == 5) selected @endif
                                                                        value="5">5</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label
                                                                class="col-md-6 col-form-label">{{ __('Total Score') }}</label>
                                                            <div class="col-md-6 parent-group">
                                                                <input type="text" class="form-control"
                                                                    value="{{ $record->inherentRisk->total_impact ?? '' }}"
                                                                    disabled>
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

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">{{ __('Main Process') }}</label>
                                        <div class="col-sm-10 parent-group">
                                            <input type="text" disabled class="form-control"
                                                value="{{ $record->kodeResiko->name }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">{{ __('Sub Process') }}</label>
                                        <div class="col-sm-10 input-group parent-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">
                                                    {{ $record->id_resiko }}
                                                </span>
                                            </div>
                                            <input type="text" disabled class="form-control"
                                                value="{{ $record->jenisResiko->name }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-2 col-form-label">{{ __('Proses Objective') }}</label>
                                        <div class="col-10 parent-group">
                                            <textarea required disabled name="objective" class="base-plugin--summernote"
                                                placeholder="{{ __('Proses Objective') }}">{!! $record->objective !!}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="separator separator-dashed my-5"></div>

                                    <ul class="nav nav-tabs" id="myTab_third" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="condition-tab_third" data-toggle="tab"
                                                data-target="#condition_third" type="button" role="tab"
                                                aria-controls="condition_third"
                                                aria-selected="true">{{ __('Risk Event') }}</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="criteria-tab_third" data-toggle="tab"
                                                data-target="#criteria_third" type="button" role="tab"
                                                aria-controls="criteria_third"
                                                aria-selected="true">{{ __('Risk Cause') }}</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="because-tab_third" data-toggle="tab"
                                                data-target="#because_third" type="button" role="tab"
                                                aria-controls="because_third"
                                                aria-selected="false">{{ __('Risk Impact') }}</button>
                                        </li>
                                    </ul>

                                    <div class="tab-content" id="myTabContent_third">
                                        <div class="tab-pane fade show active" id="condition_third" role="tabpanel"
                                            aria-labelledby="condition-tab_third">
                                            <div class="form-group row">
                                                <div class="col-md-12 parent-group">
                                                    <textarea disabled name="peristiwa" class="base-plugin--summernote" placeholder="{{ __('Risk Event') }}">{{ $record->peristiwa }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="criteria_third" role="tabpanel"
                                            aria-labelledby="criteria-tab_third">
                                            <div class="form-group row">
                                                <div class="col-md-12 parent-group">
                                                    <textarea disabled name="penyebab" class="base-plugin--summernote" placeholder="{{ __('Risk Cause') }}">{{ $record->penyebab }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="because_third" role="tabpanel"
                                            aria-labelledby="because-tab_third">
                                            <div class="form-group row">
                                                <div class="col-md-12 parent-group">
                                                    <textarea disabled name="dampak" class="base-plugin--summernote" placeholder="{{ __('Risk Impact') }}">{{ $record->dampak }}</textarea>
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
                            {{-- <table id="dataFilters" class="width-full">
									<tbody>
										<tr>
											<td>
												<div class="form-group row">
													<label class="col-md-2 col-form-label">{{ __('Main Process') }}</label>
													<div class="col-md-10 parent-group">
														<select id="kodeResikoCtrlCurrent" name="kode_resiko"
															class="form-control filter-control base-plugin--select2-ajax" data-post="kode_resiko"
															placeholder="{{ __('Pilih Salah Satu') }}">
															<option value="">{{ __('Pilih Salah Satu') }}</option>
															@foreach ($record->riskRegister->details as $detailRisk)
															<option value="{{ $detailRisk->main_process_id }}">
																{{ $detailRisk->mainProcess->name }}</option>
															@endforeach
														</select>
													</div>
												</div>
											</td>
										</tr>
									</tbody>
								</table> --}}

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
                                                                <select disabled required name="complexity"
                                                                    class="form-control base-plugin--select2"
                                                                    placeholder="{{ __('Pilih Salah Satu') }}">
                                                                    <option value="">{{ __('Pilih Salah Satu') }}
                                                                    </option>
                                                                    <option
                                                                        @if ($record->currentRisk->complexity == 1) selected @endif
                                                                        value="1">1</option>
                                                                    <option
                                                                        @if ($record->currentRisk->complexity == 2) selected @endif
                                                                        value="2">2</option>
                                                                    <option
                                                                        @if ($record->currentRisk->complexity == 3) selected @endif
                                                                        value="3">3</option>
                                                                    <option
                                                                        @if ($record->currentRisk->complexity == 4) selected @endif
                                                                        value="4">4</option>
                                                                    <option
                                                                        @if ($record->currentRisk->complexity == 5) selected @endif
                                                                        value="5">5</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label
                                                                class="col-md-6 col-form-label">{{ __('Volume (35%)') }}</label>
                                                            <div class="col-md-6 parent-group">
                                                                <select disabled required name="volume"
                                                                    class="form-control base-plugin--select2"
                                                                    placeholder="{{ __('Pilih Salah Satu') }}">
                                                                    <option value="">{{ __('Pilih Salah Satu') }}
                                                                    </option>
                                                                    <option
                                                                        @if ($record->currentRisk->volume == 1) selected @endif
                                                                        value="1">1</option>
                                                                    <option
                                                                        @if ($record->currentRisk->volume == 2) selected @endif
                                                                        value="2">2</option>
                                                                    <option
                                                                        @if ($record->currentRisk->volume == 3) selected @endif
                                                                        value="3">3</option>
                                                                    <option
                                                                        @if ($record->currentRisk->volume == 4) selected @endif
                                                                        value="4">4</option>
                                                                    <option
                                                                        @if ($record->currentRisk->volume == 5) selected @endif
                                                                        value="5">5</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label
                                                                class="col-md-6 col-form-label">{{ __('Known Issue (20%)') }}</label>
                                                            <div class="col-md-6 parent-group">
                                                                <select disabled required name="known_issue"
                                                                    class="form-control base-plugin--select2"
                                                                    placeholder="{{ __('Pilih Salah Satu') }}">
                                                                    <option value="">{{ __('Pilih Salah Satu') }}
                                                                    </option>
                                                                    <option
                                                                        @if ($record->currentRisk->known_issue == 1) selected @endif
                                                                        value="1">1</option>
                                                                    <option
                                                                        @if ($record->currentRisk->known_issue == 2) selected @endif
                                                                        value="2">2</option>
                                                                    <option
                                                                        @if ($record->currentRisk->known_issue == 3) selected @endif
                                                                        value="3">3</option>
                                                                    <option
                                                                        @if ($record->currentRisk->known_issue == 4) selected @endif
                                                                        value="4">4</option>
                                                                    <option
                                                                        @if ($record->currentRisk->known_issue == 5) selected @endif
                                                                        value="5">5</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label
                                                                class="col-md-6 col-form-label">{{ __('Changing Process & People (15%)') }}</label>
                                                            <div class="col-md-6 parent-group">
                                                                <select disabled required name="chaning_process"
                                                                    class="form-control base-plugin--select2"
                                                                    placeholder="{{ __('Pilih Salah Satu') }}">
                                                                    <option value="">{{ __('Pilih Salah Satu') }}
                                                                    </option>
                                                                    <option
                                                                        @if ($record->currentRisk->chaning_process == 1) selected @endif
                                                                        value="1">1</option>
                                                                    <option
                                                                        @if ($record->currentRisk->chaning_process == 2) selected @endif
                                                                        value="2">2</option>
                                                                    <option
                                                                        @if ($record->currentRisk->chaning_process == 3) selected @endif
                                                                        value="3">3</option>
                                                                    <option
                                                                        @if ($record->currentRisk->chaning_process == 4) selected @endif
                                                                        value="4">4</option>
                                                                    <option
                                                                        @if ($record->currentRisk->chaning_process == 5) selected @endif
                                                                        value="5">5</option>
                                                                </select>
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
                                                                <input type="text" class="form-control"
                                                                    value="{{ $record->currentRisk->total_likehood ?? '' }}"
                                                                    disabled>
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
                                                                <select disabled required name="materiality"
                                                                    class="form-control base-plugin--select2"
                                                                    placeholder="{{ __('Pilih Salah Satu') }}">
                                                                    <option value="">{{ __('Pilih Salah Satu') }}
                                                                    </option>
                                                                    <option
                                                                        @if ($record->currentRisk->materiality == 1) selected @endif
                                                                        value="1">1</option>
                                                                    <option
                                                                        @if ($record->currentRisk->materiality == 2) selected @endif
                                                                        value="2">2</option>
                                                                    <option
                                                                        @if ($record->currentRisk->materiality == 3) selected @endif
                                                                        value="3">3</option>
                                                                    <option
                                                                        @if ($record->currentRisk->materiality == 4) selected @endif
                                                                        value="4">4</option>
                                                                    <option
                                                                        @if ($record->currentRisk->materiality == 5) selected @endif
                                                                        value="5">5</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label
                                                                class="col-md-6 col-form-label">{{ __('Legal & Compliance (30%)') }}</label>
                                                            <div class="col-md-6 parent-group">
                                                                <select disabled required name="legal"
                                                                    class="form-control base-plugin--select2"
                                                                    placeholder="{{ __('Pilih Salah Satu') }}">
                                                                    <option value="">{{ __('Pilih Salah Satu') }}
                                                                    </option>
                                                                    <option
                                                                        @if ($record->currentRisk->legal == 1) selected @endif
                                                                        value="1">1</option>
                                                                    <option
                                                                        @if ($record->currentRisk->legal == 2) selected @endif
                                                                        value="2">2</option>
                                                                    <option
                                                                        @if ($record->currentRisk->legal == 3) selected @endif
                                                                        value="3">3</option>
                                                                    <option
                                                                        @if ($record->currentRisk->legal == 4) selected @endif
                                                                        value="4">4</option>
                                                                    <option
                                                                        @if ($record->currentRisk->legal == 5) selected @endif
                                                                        value="5">5</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label
                                                                class="col-md-6 col-form-label">{{ __('Operational (30%)') }}</label>
                                                            <div class="col-md-6 parent-group">
                                                                <select disabled required name="operational"
                                                                    class="form-control base-plugin--select2"
                                                                    placeholder="{{ __('Pilih Salah Satu') }}">
                                                                    <option value="">{{ __('Pilih Salah Satu') }}
                                                                    </option>
                                                                    <option
                                                                        @if ($record->currentRisk->operational == 1) selected @endif
                                                                        value="1">1</option>
                                                                    <option
                                                                        @if ($record->currentRisk->operational == 2) selected @endif
                                                                        value="2">2</option>
                                                                    <option
                                                                        @if ($record->currentRisk->operational == 3) selected @endif
                                                                        value="3">3</option>
                                                                    <option
                                                                        @if ($record->currentRisk->operational == 4) selected @endif
                                                                        value="4">4</option>
                                                                    <option
                                                                        @if ($record->currentRisk->operational == 5) selected @endif
                                                                        value="5">5</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label
                                                                class="col-md-6 col-form-label">{{ __('Total Score') }}</label>
                                                            <div class="col-md-6 parent-group">
                                                                <input type="text" class="form-control"
                                                                    value="{{ $record->currentRisk->total_impact ?? '' }}"
                                                                    disabled>
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

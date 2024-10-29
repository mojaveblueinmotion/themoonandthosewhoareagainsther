@extends('layouts.page')

@section('page')
    <form action="{{ route($routes . '.update', $record->id) }}" method="POST">
        @csrf
        @method('PATCH')
        {{-- <input type="hidden" name="formTab" value="{{ $tab }}"> --}}
        <div class="row">
            <div class="col-md-3">
                @include($views . '.includes.tab-create-aside')
            </div>
            <div class="col-md-9">
                <?php $user_kepada = App\Models\Auth\User::where('position_id', '!=', null)->get(); ?>
                <div class="card card-custom" style="height:100%;">
                    <div class="card-header py-3">
                        <div class="card-title">
                            <h3 class="card-label font-weight-bolder text-dark" id="CtrlCardLabel">1. Risk Register</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane fade active show" id="first_tab" role="tabpanel"
                                aria-labelledby="first_tab">
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
                                        <div class="form-group row">
                                            <label class="col-md-4 col-form-label">{{ __('Jenis Audit') }}</label>
                                            <div class="col-md-8 parent-group">
                                                <select name="type_id"
                                                    class="form-control base-plugin--select2-ajax type_id"
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
                                    <div class="col-6">
                                        <div class="form-group row">
                                            <label class="col-md-4 col-form-label">{{ __('Subject Audit') }}</label>
                                            <div class="col-md-8 parent-group">
                                                <select id="unitKerjaCtrl" disabled name="unit_kerja_id"
                                                    class="form-control base-plugin--select2-ajax"
                                                    data-url="{{ rut('ajax.selectStruct', 'all') }}"
                                                    placeholder="{{ __('Pilih Salah Satu') }}">
                                                    <option value="">{{ __('Pilih Salah Satu') }}</option>
                                                    @if (!empty($record->riskRegister->unitKerja))
                                                        <option value="{{ $record->riskRegister->unitKerja->id }}"
                                                            selected>
                                                            {{ $record->riskRegister->unitKerja->name }}</option>
                                                    @endif
                                                </select>
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
                                            <label class="col-md-4 col-form-label">{{ __('Subject Audit') }}</label>
                                            <div class="col-md-8 parent-group">
                                                <select id="unitKerjaCtrl" disabled name="unit_kerja_id"
                                                    class="form-control base-plugin--select2-ajax"
                                                    data-url="{{ rut('ajax.selectStruct', 'all') }}"
                                                    placeholder="{{ __('Pilih Salah Satu') }}">
                                                    <option value="">{{ __('Pilih Salah Satu') }}</option>
                                                    @if (!empty($record->riskRegister->unitKerja))
                                                        <option value="{{ $record->riskRegister->unitKerja->id }}"
                                                            selected>
                                                            {{ $record->riskRegister->unitKerja->name }}</option>
                                                    @endif
                                                </select>
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
                                <div class="form-group row">
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
                                </div>
                                <hr>

								<div class="row">
									<div class="col-md-6">
										<div class="form-group row">
											<label class="col-md-4 col-form-label">{{ __('Complexity (30%)') }}</label>
											<div class="col-md-8 parent-group">
                                                <input type="text" id="complexity" disabled name="complexity" class="form-control"
                                                    placeholder="{{ __('Complexity') }}" value="">
											</div>
										</div>
										<div class="form-group row">
											<label class="col-md-4 col-form-label">{{ __('Volume (35%)') }}</label>
											<div class="col-md-8 parent-group">
												<input type="text" id="volume" disabled name="volume" class="form-control"
                                                    placeholder="{{ __('Volume') }}" value="">
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group row">
											<label class="col-md-4 col-form-label">{{ __('Known Issue (20%)') }}</label>
											<div class="col-md-8 parent-group">
												<input type="text" id="known_issue" disabled name="known_issue" class="form-control"
                                                    placeholder="{{ __('Known Issue') }}" value="">
											</div>
										</div>
										<div class="form-group row">
											<label class="col-md-4 col-form-label">{{ __('Changing Process & People (15%)') }}</label>
											<div class="col-md-8 parent-group">
												<input type="text" id="chaning_process" disabled name="chaning_process" class="form-control"
                                                    placeholder="{{ __('Changing Process') }}" value="">
											</div>
										</div>
									</div>
								</div>
                                <div class="row">
									<div class="col-md-6">
										<div class="form-group row">
											<label class="col-md-4 col-form-label">{{ __('Materiality (40%)') }}</label>
											<div class="col-md-8 parent-group">
                                                <input type="text" id="materiality" disabled name="materiality" class="form-control"
                                                    placeholder="{{ __('Materiality') }}" value="">
											</div>
										</div>
										<div class="form-group row">
											<label class="col-md-4 col-form-label">{{ __('Legal & Compliance (30%)') }}</label>
											<div class="col-md-8 parent-group">
												<input type="text" id="legal" disabled name="legal" class="form-control"
                                                    placeholder="{{ __('Legal & Compliance') }}" value="">
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group row">
											<label class="col-md-4 col-form-label">{{ __('Operational (30%)') }}</label>
											<div class="col-md-8 parent-group">
												<input type="text" id="operational" disabled name="operational" class="form-control"
                                                    placeholder="{{ __('Operational') }}" value="">
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
                                            <label class="col-md-4 col-form-label">{{ __('Subject Audit') }}</label>
                                            <div class="col-md-8 parent-group">
                                                <select id="unitKerjaCtrl" disabled name="unit_kerja_id"
                                                    class="form-control base-plugin--select2-ajax"
                                                    data-url="{{ rut('ajax.selectStruct', 'all') }}"
                                                    placeholder="{{ __('Pilih Salah Satu') }}">
                                                    <option value="">{{ __('Pilih Salah Satu') }}</option>
                                                    @if (!empty($record->riskRegister->unitKerja))
                                                        <option value="{{ $record->riskRegister->unitKerja->id }}"
                                                            selected>
                                                            {{ $record->riskRegister->unitKerja->name }}</option>
                                                    @endif
                                                </select>
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
                                </table>

                                <div class="row">
									<div class="col-md-6">
										<div class="form-group row">
											<label class="col-md-4 col-form-label">{{ __('Complexity (30%)') }}</label>
											<div class="col-md-8 parent-group">
                                                <input type="text" id="complexity_current" disabled name="complexity" class="form-control"
                                                    placeholder="{{ __('Complexity') }}" value="">
											</div>
										</div>
										<div class="form-group row">
											<label class="col-md-4 col-form-label">{{ __('Volume (35%)') }}</label>
											<div class="col-md-8 parent-group">
												<input type="text" id="volume_current" disabled name="volume" class="form-control"
                                                    placeholder="{{ __('Volume') }}" value="">
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group row">
											<label class="col-md-4 col-form-label">{{ __('Known Issue (20%)') }}</label>
											<div class="col-md-8 parent-group">
												<input type="text" id="known_issue_current" disabled name="known_issue" class="form-control"
                                                    placeholder="{{ __('Known Issue') }}" value="">
											</div>
										</div>
										<div class="form-group row">
											<label class="col-md-4 col-form-label">{{ __('Changing Process & People (15%)') }}</label>
											<div class="col-md-8 parent-group">
												<input type="text" id="chaning_process_current" disabled name="chaning_process" class="form-control"
                                                    placeholder="{{ __('Changing Process') }}" value="">
											</div>
										</div>
									</div>
								</div>
                                <div class="row">
									<div class="col-md-6">
										<div class="form-group row">
											<label class="col-md-4 col-form-label">{{ __('Materiality (40%)') }}</label>
											<div class="col-md-8 parent-group">
                                                <input type="text" id="materiality_current" disabled name="materiality" class="form-control"
                                                    placeholder="{{ __('Materiality') }}" value="">
											</div>
										</div>
										<div class="form-group row">
											<label class="col-md-4 col-form-label">{{ __('Legal & Compliance (30%)') }}</label>
											<div class="col-md-8 parent-group">
												<input type="text" id="legal_current" disabled name="legal" class="form-control"
                                                    placeholder="{{ __('Legal & Compliance') }}" value="">
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group row">
											<label class="col-md-4 col-form-label">{{ __('Operational (30%)') }}</label>
											<div class="col-md-8 parent-group">
												<input type="text" id="operational_current" disabled name="operational" class="form-control"
                                                    placeholder="{{ __('Operational') }}" value="">
											</div>
										</div>
									</div>
								</div>
                                <div class="row">
                                    <div class="table-responsive">
                                        @if (isset($tableStruct['datatable_1']))
                                            <table id="datatable_1" class="table-bordered is-datatable table"
                                                style="width: 100%;" data-url="{{ $tableStruct['url'] }}"
                                                data-paging="{{ $paging ?? true }}" data-info="{{ $info ?? true }}">
                                                <thead>
                                                    <tr>
                                                        @foreach ($tableStruct['datatable_1'] as $struct)
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
                            <div class="tab-pane fade" id="fourth_tab" role="tabpanel" aria-labelledby="fourth_tab">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-md-2 col-form-label">
                                                {{ __('Risk Rating') }}
                                            </label>
                                            <div class="col-md-10 parent-group">
                                                <select name="risk_rating_id"
                                                    class="form-control base-plugin--select2-ajax"
                                                    data-url="{{ rut('ajax.selectRiskRating', 'all') }}" disabled
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
                            </div>
                        </div>
                    </div>
                    @if ($record->checkAction('approval', $perms))
                        <div class="card-footer d-none" id="cardFooter">
                            <div class="d-flex justify-content-between">
                                @include('layouts.forms.btnBack')
                                @include('layouts.forms.btnDropdownApproval')
                            </div>
                        </div>
                        @include('layouts.forms.modalReject')
                    @endif
                </div>
            </div>
        </div>
    </form>
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
                if (activeTabText === '4. Risk Rating') {
                    $('#cardFooter').removeClass('d-none');
                } else {
                    $('#cardFooter').addClass('d-none');
                }
            });
        });
    </script>
@endpush

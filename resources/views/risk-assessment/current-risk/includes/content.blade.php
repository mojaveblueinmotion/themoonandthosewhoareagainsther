@extends('layouts.page')

@section('content-body')
    <div class="flex-column-fluid">
        <form action="{{ route($routes . '.submitSave', $record->id) }}" method="POST" autocomplete="off">
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            <div class="container-fluid">
                <div class="card card-custom">
                    <div class="card-header">
                        <h3 class="card-title">Residual Risk</h3>
                        <div class="card-toolbar">
                        @section('card-toolbar')
                            @include('layouts.forms.btnBackTop')
                        @show
                    </div>
                </div>
                <div class="card-body">
                    @include($views . '.includes.notes')
                    @include($views . '.includes.header')
                </div>
            </div>
        </div>
		<div class="container-fluid mt-5">
			<div class="row">
				<div class="col-md-12">
					<div class="card card-custom">
						<div class="card-header">
                            <h3 class="card-title">Existing & Compensating Control</h3>
                            <div class="card-toolbar">
                            </div>
                        </div>
                        <div class="card-body">
    						<div class="row">
								<div class="col-md-12">
									<div class="form-group row">
										<label class="col-md-2 col-form-label">{{ __('Condition') }}</label>
										<div class="col-md-10 parent-group">
											<textarea data-height="200"
												name="condition"
												class="base-plugin--summernote"
												placeholder="{{ __('Condition') }}"
												>{{ $record->riskRegisterDetail->condition }}</textarea>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-md-2 col-form-label">{{ __('Notes') }}</label>
										<div class="col-md-10 parent-group">
											<textarea data-height="200"
												name="notes"
												class="base-plugin--summernote"
												placeholder="{{ __('Notes') }}"
												>{{ $record->riskRegisterDetail->notes }}</textarea>
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
                            <h3 class="card-title">Likelihood</h3>
                            <div class="card-toolbar">
                            </div>
                        </div>
                        <div class="card-body">
    						<div class="row">
								<div class="col-md-6">
									<div class="form-group row">
										<label class="col-md-6 col-form-label">{{ __('Complexity (30%)') }}</label>
										<div class="col-md-6 parent-group">
											<select required name="complexity" class="form-control base-plugin--select2 complexity"
												placeholder="{{ __('Pilih Salah Satu') }}">
												<option value="">{{ __('Pilih Salah Satu') }}</option>
        										<option @if($record->complexity == 1) selected @endif value="1">1</option>
        										<option @if($record->complexity == 2) selected @endif value="2">2</option>
        										<option @if($record->complexity == 3) selected @endif value="3">3</option>
        										<option @if($record->complexity == 4) selected @endif value="4">4</option>
        										<option @if($record->complexity == 5) selected @endif value="5">5</option>
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-md-6 col-form-label">{{ __('Volume (35%)') }}</label>
										<div class="col-md-6 parent-group">
											<select required name="volume" class="form-control base-plugin--select2 volume"
												placeholder="{{ __('Pilih Salah Satu') }}">
												<option value="">{{ __('Pilih Salah Satu') }}</option>
        										<option @if($record->volume == 1) selected @endif value="1">1</option>
        										<option @if($record->volume == 2) selected @endif value="2">2</option>
        										<option @if($record->volume == 3) selected @endif value="3">3</option>
        										<option @if($record->volume == 4) selected @endif value="4">4</option>
        										<option @if($record->volume == 5) selected @endif value="5">5</option>
											</select>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group row">
										<label class="col-md-6 col-form-label">{{ __('Known Issue (20%)') }}</label>
										<div class="col-md-6 parent-group">
											<select required name="known_issue" class="form-control base-plugin--select2 known_issue"
												placeholder="{{ __('Pilih Salah Satu') }}">
												<option value="">{{ __('Pilih Salah Satu') }}</option>
        										<option @if($record->known_issue == 1) selected @endif value="1">1</option>
        										<option @if($record->known_issue == 2) selected @endif value="2">2</option>
        										<option @if($record->known_issue == 3) selected @endif value="3">3</option>
        										<option @if($record->known_issue == 4) selected @endif value="4">4</option>
        										<option @if($record->known_issue == 5) selected @endif value="5">5</option>
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-md-6 col-form-label">{{ __('Changing Process & People (15%)') }}</label>
										<div class="col-md-6 parent-group">
											<select required name="chaning_process" class="form-control base-plugin--select2 chaning_process"
												placeholder="{{ __('Pilih Salah Satu') }}">
												<option value="">{{ __('Pilih Salah Satu') }}</option>
        										<option @if($record->chaning_process == 1) selected @endif value="1">1</option>
        										<option @if($record->chaning_process == 2) selected @endif value="2">2</option>
        										<option @if($record->chaning_process == 3) selected @endif value="3">3</option>
        										<option @if($record->chaning_process == 4) selected @endif value="4">4</option>
        										<option @if($record->chaning_process == 5) selected @endif value="5">5</option>
											</select>
										</div>
									</div>
								</div>
							</div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
										<label class="col-md-6 col-form-label">{{ __('Total Score') }}</label>
										<div class="col-md-6 parent-group">
                                            <input type="text" id="total_likehood" class="form-control" name="total_likehood" value="{{$record->total_likehood ?? ''}}" readonly>
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
										<label class="col-md-6 col-form-label">{{ __('Materiality (40%)') }}</label>
										<div class="col-md-6 parent-group">
											<select required name="materiality" class="form-control base-plugin--select2 materiality"
												placeholder="{{ __('Pilih Salah Satu') }}">
												<option value="">{{ __('Pilih Salah Satu') }}</option>
        										<option @if($record->materiality == 1) selected @endif value="1">1</option>
        										<option @if($record->materiality == 2) selected @endif value="2">2</option>
        										<option @if($record->materiality == 3) selected @endif value="3">3</option>
        										<option @if($record->materiality == 4) selected @endif value="4">4</option>
        										<option @if($record->materiality == 5) selected @endif value="5">5</option>
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-md-6 col-form-label">{{ __('Legal & Compliance (30%)') }}</label>
										<div class="col-md-6 parent-group">
											<select required name="legal" class="form-control base-plugin--select2 legal"
												placeholder="{{ __('Pilih Salah Satu') }}">
												<option value="">{{ __('Pilih Salah Satu') }}</option>
        										<option @if($record->legal == 1) selected @endif value="1">1</option>
        										<option @if($record->legal == 2) selected @endif value="2">2</option>
        										<option @if($record->legal == 3) selected @endif value="3">3</option>
        										<option @if($record->legal == 4) selected @endif value="4">4</option>
        										<option @if($record->legal == 5) selected @endif value="5">5</option>
											</select>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group row">
										<label class="col-md-6 col-form-label">{{ __('Operational (30%)') }}</label>
										<div class="col-md-6 parent-group">
											<select required name="operational" class="form-control base-plugin--select2 operational"
												placeholder="{{ __('Pilih Salah Satu') }}">
												<option value="">{{ __('Pilih Salah Satu') }}</option>
        										<option @if($record->operational == 1) selected @endif value="1">1</option>
        										<option @if($record->operational == 2) selected @endif value="2">2</option>
        										<option @if($record->operational == 3) selected @endif value="3">3</option>
        										<option @if($record->operational == 4) selected @endif value="4">4</option>
        										<option @if($record->operational == 5) selected @endif value="5">5</option>
											</select>
										</div>
									</div>
                                    <div class="form-group row">
										<label class="col-md-6 col-form-label">{{ __('Total Score') }}</label>
										<div class="col-md-6 parent-group">
                                            <input type="text" id="total_impact" class="form-control" name="total_impact" value="{{$record->total_impact ?? ''}}" readonly>
                                        </div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
        {{-- <div class="container-fluid mt-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-custom">
                        <div class="card-header">
                            <h3 class="card-title">Pengendalian</h3>
                            <div class="card-toolbar">
                            </div>
                        </div>
                        <div class="card-body">
                            @include($views . '.detail.detail-grid-laporan')
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        @if (request()->route()->getName() !=
                $routes . '.detail.show')
            <div class="container-fluid mt-5">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-custom">
                            <div class="card-header">
                                <h3 class="card-title">Alur Persetujuan</h3>
                                <div class="card-toolbar">
                                </div>
                            </div>
                            <div class="card-body text-center">
                                @php
                                    $colors = [
                                        1 => 'primary',
                                        2 => 'info',
                                    ];
                                @endphp
                                @if ($menu = \App\Models\Globals\Menu::where('module', 'risk-assessment.current-risk')->first())
                                    @if ($menu->flows()->get()->groupBy('order')->count() == null)
                                        <span class="label label-light-info font-weight-bold label-inline"
                                            data-toggle="tooltip">Belum
                                            memiliki Alur Persetujuan.</span>
                                    @else
                                        @foreach ($orders = $menu->flows()->get()->groupBy('order') as $i => $flows)
                                            @foreach ($flows as $j => $flow)
                                                <span
                                                    class="label label-light-{{ $colors[$flow->type] }} font-weight-bold label-inline"
                                                    data-toggle="tooltip"
                                                    title="{{ $flow->show_type }}">{{ $flow->role->name }}</span>
                                                @if (!($i === $orders->keys()->last() && $j === $flows->keys()->last()))
                                                    <i class="fas fa-angle-double-right text-muted mx-2"></i>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-custom">
                            <div class="card-header">
                                <h3 class="card-title">Aksi</h3>
                                <div class="card-toolbar">
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-end">
                                    Sebelum submit, pastikan data sesuai & alur persetujuan terisi.
                                    @include('layouts.forms.btnDropdownSubmit')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
</div>
</div>
@endsection
@section('buttons')
@endsection


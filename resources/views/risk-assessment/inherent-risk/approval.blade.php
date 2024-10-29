@extends('layouts.page')

@section('content-body')
<div class=" flex-column-fluid">
@method('POST')
		<div class="container-fluid">
			<div class="card card-custom">
				<div class="card-header">
					<h3 class="card-title">Inherent Risk</h3>
					<div class="card-toolbar">
						@section('card-toolbar')
						@include('layouts.forms.btnBackTop')
						@show
					</div>
				</div>
				<div class="card-body">
					@include($views.'.includes.notes')
					@include($views.'.includes.header')
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
											<select required disabled name="complexity" class="form-control base-plugin--select2"
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
											<select required disabled name="volume" class="form-control base-plugin--select2"
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
											<select required disabled name="known_issue" class="form-control base-plugin--select2"
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
											<select required disabled name="chaning_process" class="form-control base-plugin--select2"
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
                                            <input type="text" class="form-control" value="{{$record->total_likehood ?? ''}}" disabled>
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
											<select required disabled name="materiality" class="form-control base-plugin--select2"
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
											<select required disabled name="legal" class="form-control base-plugin--select2"
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
											<select required disabled name="operational" class="form-control base-plugin--select2"
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
                                            <input type="text" class="form-control" value="{{$record->total_impact ?? ''}}"  disabled>
                                        </div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			@if ($record->checkAction('approval', $perms))
            <div class="card-footer">
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
@endsection
@section('buttons')
@endsection


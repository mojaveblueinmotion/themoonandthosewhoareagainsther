@extends('layouts.modal')
@section('modal-title')
    {!! $title !!}
@endsection
@section('action', rut($routes . '.detailUpdate', $detail->id))

@section('modal-body')
@method('PATCH')
<div class="row">
    <div class="col-sm-12">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item active" role="presentation">
                <button class="nav-link active" id="condition-tab" data-toggle="tab" data-target="#condition" type="button"
                    role="tab" aria-controls="condition" aria-selected="true">{{ __('Audit Finding') }}</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="criteria-tab" data-toggle="tab" data-target="#criteria" type="button"
                    role="tab" aria-controls="criteria" aria-selected="false">{{ __('Kriteria') }}</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="because-tab" data-toggle="tab" data-target="#because" type="button"
                    role="tab" aria-controls="because" aria-selected="false">{{ __('Sebab') }}</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="consequence-tab" data-toggle="tab" data-target="#consequence"
                    type="button" role="tab" aria-controls="consequence" aria-selected="false">{{ __('Risk & Impact') }}</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="recommendation-tab" data-toggle="tab" data-target="#rekomendasi"
                    type="button" role="tab" aria-controls="recommendation"
                    aria-selected="false">{{ __('Rekomendasi') }}</button>
            </li>
        </ul>
    </div>

    <div class="col-sm-12">
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="condition" role="tabpanel" aria-labelledby="condition-tab">
                <div class="form-group row">
                    <div class="col-md-12 parent-group">
                        <textarea class="form-control base-plugin--summernote" disabled>{!! $detail->condition !!}</textarea>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="criteria" role="tabpanel" aria-labelledby="criteria-tab">
                <div class="form-group row">
                    <div class="col-md-12 parent-group">
                        <textarea class="form-control base-plugin--summernote" disabled>{!! $detail->criteria !!}</textarea>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="because" role="tabpanel" aria-labelledby="because-tab">
                <div class="form-group row">
                    <div class="col-md-12 parent-group">
                        <textarea class="form-control base-plugin--summernote" disabled>{!! $detail->because !!}</textarea>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="consequence" role="tabpanel" aria-labelledby="consequence-tab">
                <div class="form-group row">
                    <div class="col-md-12 parent-group">
                        <textarea class="form-control base-plugin--summernote" disabled>{!! $detail->consequence !!}</textarea>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="rekomendasi" role="tabpanel" aria-labelledby="rekomendasi-tab">
                <div class="form-group row">
                    <div class="col-md-12 parent-group">
                        <textarea class="form-control " name="recommendation">{!! $detail->recommendation !!}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="separator separator-dashed my-5"></div>
<div class="form-group row">
    <label class="col-md-12 col-form-label">{{ __('Tanggapan / Komitmen Tindak Lanjut Perbaikan') }}</label>
    <div class="col-md-12 parent-group">
        <textarea name="commitment" class="form-control base-plugin--summernote"
            placeholder="{{ __('Tanggapan / Komitmen Tindak Lanjut Perbaikan') }}" data-height="200">{!! $detail->regItem->commitment ?? '' !!}</textarea>
    </div>
</div>
<div class="form-group row">
    <label class="col-md-12 col-form-label">{{ __('Batas Waktu') }}</label>
    <div class="col-md-12 parent-group">
        <input type="text" name="deadline" class="form-control base-plugin--datepicker"
            data-options='@json([
                'startDate' => "",
                'endDate' => '',
            ])' @if(!empty($detail->regItem->deadline)) value="{{ $detail->regItem->show_deadline }}" @endif
        placeholder="{{ __('Batas Waktu') }}">
    </div>
</div>
@endsection

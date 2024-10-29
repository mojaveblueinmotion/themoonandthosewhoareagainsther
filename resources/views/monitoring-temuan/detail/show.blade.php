@extends('layouts.modal')
@section('modal-title')
    {!! $title !!}
@endsection

@section('modal-body')
    <div class="row">
        <div class="col-md-12">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">{{ __('Lingkup Audit') }}</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" value="{{ $record->sample->agenda->aspect->name }}" disabled>
                </div>
            </div>
        </div>
        {{-- <div class="col-md-6">
            <div class="form-group row">
                <label class="col-md-4 col-form-label">{{ __('Dokumen/Berkas') }}</label>
                <div class="col-md-8 parent-group">
                    <select class="form-control base-plugin--select2-ajax doc-item" name="no_contract" id="documentCtrl"
                    data-url-origin="{{ rut('ajax.selectDocItem', 'by_aspect') }}"
                    placeholder="{{ __('Pilih Salah Satu') }}">
                    <option value="">{{ __('Pilih Salah Satu') }}</option>
                </select>
                </div>
            </div>
        </div> --}}
        <div class="col-md-12">
            <div class="form-group row">
                <label class="col-md-2 col-form-label">{{ __('Langkah Kerja') }}</label>
                <div class="col-md-10 parent-group">
                    <select disabled name="apm_detail_id" class="form-control base-plugin--select2-ajax apm_detail_id"
                        data-url="{{ rut('ajax.selectDetailApm2') }}"
                        data-url-origin="{{ rut('ajax.selectDetailApm2') }}"
                        placeholder="{{ __('Pilih Salah Satu') }}">
                        <option value="">{{ __('Pilih Salah Satu') }}</option>
                        <option value="{{ $record->sample->agenda->id }}" selected>{{ $record->sample->agenda->procedure }}</option>
                    </select>
                    <input type="text" value="{{ $summary->id }}" class="form-control summary_id" hidden>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group row">

                <label class="col-md-2 col-form-label">{{ __('Status Review') }}</label>
                <div class="col-md-10 parent-group">
                    <select id="isfindingCtrl" class="form-control base-plugin--select2" disabled>
                        <option @if ($detail->is_finding == 'finding') selected @endif value="finding">Finding</option>
                        <option @if ($detail->is_finding == 'non-finding') selected @endif value="non-finding">Non-Finding</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-2 col-form-label">{{ __('Judul Audit') }}</label>
        <div class="col-md-10 parent-group">
            <input type="text" name="description" class="form-control" value="{{ $detail->description }}" disabled>
        </div>
    </div>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="criteria-tab" data-toggle="tab" data-target="#criteria" type="button"
                role="tab" aria-controls="criteria" aria-selected="true">{{ __('Kriteria') }}</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="condition-tab" data-toggle="tab" data-target="#condition" type="button"
                role="tab" aria-controls="condition" aria-selected="true">{{ __('Audit Finding') }}</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="because-tab" data-toggle="tab" data-target="#because" type="button" role="tab"
                aria-controls="because" aria-selected="false">{{ __('Sebab') }}</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="consequence-tab" data-toggle="tab" data-target="#consequence" type="button"
                role="tab" aria-controls="consequence" aria-selected="false">{{ __('Risk & Impact') }}</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="lampiran-tab" data-toggle="tab" data-target="#lampiran" type="button"
                role="tab" aria-controls="lampiran" aria-selected="false">{{ __('Lampiran') }}</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="recommendation-tab" data-toggle="tab" data-target="#recommendation" type="button"
                role="tab" aria-controls="recommendation" aria-selected="false">{{ __('Rekomendasi') }}</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="commitment-tab" data-toggle="tab" data-target="#commitment" type="button"
                role="tab" aria-controls="commitment" aria-selected="false">Komentar Manajemen</button>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade" id="condition" role="tabpanel" aria-labelledby="condition-tab">
            <div class="form-group row">
                <div class="col-md-12 parent-group">
                    <textarea name="condition" class="base-plugin--summernote" placeholder="{{ __('Audit Finding') }}" disabled>{!! $detail->condition !!}</textarea>
                </div>
            </div>
        </div>
        <div class="tab-pane fade show active" id="criteria" role="tabpanel" aria-labelledby="criteria-tab">
            <div class="form-group row">
                <div class="col-md-12 parent-group">
                    <textarea name="criteria" class="base-plugin--summernote" placeholder="{{ __('Kriteria') }}" disabled>{!! $detail->criteria !!}</textarea>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="because" role="tabpanel" aria-labelledby="because-tab">
            <div class="form-group row">
                <div class="col-md-12 parent-group">
                    <textarea name="because" class="base-plugin--summernote" placeholder="{{ __('Sebab') }}" disabled>{!! $detail->because !!}</textarea>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="consequence" role="tabpanel" aria-labelledby="consequence-tab">
            <div class="form-group row">
                <div class="col-md-12 parent-group">
                    <textarea name="consequence" class="base-plugin--summernote" placeholder="{{ __('Risk & Impact') }}" disabled>{!! $detail->consequence !!}</textarea>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="recommendation" role="tabpanel" aria-labelledby="recommendation-tab">
            <div class="form-group row">
                <div class="col-md-12 parent-group">
                    <textarea name="recommendation" class="base-plugin--summernote" placeholder="{{ __('Rekomendasi') }}" disabled>{!! $detail->recommendation !!}</textarea>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="lampiran" role="tabpanel" aria-labelledby="lampiran-tab">
            <div class="form-group row">
                <div class="col-md-12 parent-group">
                    @forelse ($detail->files('conducting.sample')->where('flag', 'attachments')->get() as $file)
                        <div class="progress-container w-100" data-uid="{{ $file->id }}">
                            <div class="alert alert-custom alert-light fade show success-uploaded mb-0 mt-2 px-4 py-2"
                                role="alert">
                                <div class="alert-icon">
                                    <i class="{{ $file->file_icon }}"></i>
                                </div>
                                <div class="alert-text text-left">
                                    <input type="hidden" name="attachments[files_ids][]" value="{{ $file->id }}">
                                    <div>Uploaded File:</div>
                                    <a href="{{ $file->file_url }}" target="_blank" class="text-primary">
                                        {{ $file->file_name }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-form-label">{{ __('Data tidak tersedia!') }}</div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="commitment" role="tabpanel" aria-labelledby="commitment-tab">
            <div class="form-group row">
                <label class="col-md-12 col-form-label">{{ __('Batas Waktu') }}</label>
                <div class="col-md-12 parent-group">
                    <input type="text" name="deadline" id="deadlineCtrl" class="form-control base-plugin--datepicker"
                        data-options='@json([
                            'startDate' => now()->format('d/m/Y'),
                            'endDate' => '',
                        ])'
                        disabled
                        @if ($detail->deadline) value="{{ $detail->deadline->format('d/m/Y') }}" @endif
                        placeholder="{{ __('Batas Waktu') }}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12 parent-group" id="commitmentCtrl2">
                    <textarea name="commitment" class="form-control base-plugin--summernote" id="cmm2" disabled
                        placeholder="{{ __('Tanggapan / Komentar Manajemen') }}" data-height="150">{!! $detail->commitment ?? '' !!}</textarea>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('buttons')
@endsection

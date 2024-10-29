@extends('layouts.page', ['container' => 'container'])

@section('card-body')
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">{{ __('Tahun') }}</label>
                <div class="col-sm-8">
                    <input class="form-control" disabled value="{{ $summary->rkia->year }}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">{{ __('Jenis Audit') }}</label>
                <div class="col-sm-8">
                    <input class="form-control" disabled value="{{ $summary->type->name }}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-4 col-form-label">{{ __('KKA') }}</label>
                <div class="col-8 parent-group">
                    <input class="form-control no_kka" name="no_kka" placeholder="{{ __('KKA') }}"
                        value="{{ $record->sample->no_kka }} ({{ $record->sample->posting_date ? $record->sample->posting_date->translatedFormat('d F Y') : now()->format('d/m/Y') }})"
                        disabled>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">{{ __('Subjek Audit') }}</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="{{ $summary->subject->name }}" disabled>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">{{ __('LHA') }}</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control"
                        value="{{ $summary->lha ? $summary->lha->no_memo . ' (' . $summary->lha->date_memo->translatedFormat('d F Y') . ')' : '' }}"
                        disabled>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-4 col-form-label">{{ __('ID Temuan') }}</label>
                <div class="col-8 parent-group">
                    <input class="form-control no_kka" name="id_temuan" placeholder="{{ __('ID Temuan') }}"
                        value="{{ $record->id_temuan }}" disabled>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group row">
                <label class="col-2 col-form-label">{{ __('Dept Auditee') }}</label>
                <div class="col-10 parent-group">
                    <select class="form-control base-plugin--select2" disabled multiple>
                            @foreach ($summary->departmentAuditee->departments as $val)
                                <option selected>{{ $val->name }}</option>
                            @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group row">
                <label class="col-md-2 col-form-label">{{ __('Lingkup Audit') }}</label>
                <div class="col-md-10 parent-group">
                    <select disabled multiple name="aspect_id" class="form-control base-plugin--select2-ajax aspect_id"
                        data-url="{{ rut('ajax.selectAspect', [
                            'search' => 'by_ids',
                            'ids' => $summary->getAspectIds(),
                        ]) }}"
                        data-url-origin="{{ rut('ajax.selectAspect', ['search' => 'by_ids']) }}"
                        placeholder="{{ __('Pilih Salah Satu') }}">
                        <option value="">{{ __('Pilih Salah Satu') }}</option>
                        @foreach ($summary->getAspect() as $val)
                            <option value="{{ $val->id }}" selected>{{ $val->name }}</option>
                        @endforeach
                    </select>
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
    </div>
    {{-- <div class="row"> --}}
        {{-- <div class="col-md-6"> --}}
            <div class="form-group row">
                <label class="col-md-2 col-form-label">{{ __('Langkah Kerja') }}</label>
                <div class="col-md-10 parent-group">
                    <select disabled name="apm_detail_id" class="form-control base-plugin--select2-ajax apm_detail_id"
                        data-url="{{ rut('ajax.selectDetailApm2') }}" data-url-origin="{{ rut('ajax.selectDetailApm2') }}"
                        placeholder="{{ __('Pilih Salah Satu') }}">
                        <option value="">{{ __('Pilih Salah Satu') }}</option>
                        <option value="{{ $record->sample->agenda->id }}" selected>
                            {{ $record->sample->agenda->procedure }}</option>
                    </select>
                    <input type="text" value="{{ $summary->id }}" class="form-control summary_id" hidden>
                </div>
            </div>
        {{-- </div> --}}
        {{-- <div class="col-md-6"> --}}
            <div class="form-group row">

                <label class="col-md-2 col-form-label">{{ __('Status Review') }}</label>
                <div class="col-md-10 parent-group">
                    <select id="isfindingCtrl" class="form-control base-plugin--select2" disabled>
                        <option @if ($record->is_finding == 'finding') selected @endif value="finding">Finding</option>
                        <option @if ($record->is_finding == 'non-finding') selected @endif value="non-finding">Non-Finding</option>
                    </select>
                </div>
            </div>
        {{-- </div> --}}
    {{-- </div> --}}
    <div class="form-group row">
        <label class="col-2 col-form-label">{{ __('Judul Audit') }}</label>
        <div class="col-10 parent-group">
            <input class="form-control" disabled value="{{ $record->description }}">
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="criteria-tab" data-toggle="tab" data-target="#criteria" type="button"
                        role="tab" aria-controls="criteria" aria-selected="true">{{ __('Kriteria') }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="condition-tab" data-toggle="tab" data-target="#condition"
                        type="button" role="tab" aria-controls="condition"
                        aria-selected="true">{{ __('Audit Finding') }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="because-tab" data-toggle="tab" data-target="#because" type="button"
                        role="tab" aria-controls="because" aria-selected="false">{{ __('Sebab') }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="consequence-tab" data-toggle="tab" data-target="#consequence"
                        type="button" role="tab" aria-controls="consequence"
                        aria-selected="false">{{ __('Risk & Impact') }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tanggapanAuditee-tab" data-toggle="tab" data-target="#tanggapanAuditee"
                        type="button" role="tab" aria-controls="tanggapanAuditee"
                        aria-selected="false">{{ __('Tanggapan') }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="perbaikan-tab" data-toggle="tab" data-target="#perbaikan"
                        type="button" role="tab" aria-controls="perbaikan"
                        aria-selected="false">{{ __('Rencana Perbaikan') }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="reviewAuditor-tab" data-toggle="tab" data-target="#reviewAuditor"
                        type="button" role="tab" aria-controls="reviewAuditor"
                        aria-selected="false">{{ __('Review Auditor') }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="recommendation-tab" data-toggle="tab" data-target="#rekomendasi"
                        type="button" role="tab" aria-controls="recommendation"
                        aria-selected="false">{{ __('Rekomendasi') }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="komitmen-tab" data-toggle="tab" data-target="#komitmen" type="button"
                        role="tab" aria-controls="komitmen"
                        aria-selected="true">{{ __('Komentar Manajemen') }}</button>
                </li>
            </ul>
        </div>

        <div class="col-sm-12">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="condition" role="tabpanel" aria-labelledby="condition-tab">
                    <div class="form-group row">
                        <div class="col-md-12 parent-group">
                            <textarea class="form-control base-plugin--summernote" disabled>{!! $record->condition !!}</textarea>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="criteria" role="tabpanel" aria-labelledby="criteria-tab">
                    <div class="form-group row">
                        <div class="col-md-12 parent-group">
                            <textarea class="form-control base-plugin--summernote" disabled>{!! $record->criteria !!}</textarea>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="because" role="tabpanel" aria-labelledby="because-tab">
                    <div class="form-group row">
                        <div class="col-md-12 parent-group">
                            <textarea class="form-control base-plugin--summernote" disabled>{!! $record->because !!}</textarea>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="consequence" role="tabpanel" aria-labelledby="consequence-tab">
                    <div class="form-group row">
                        <div class="col-md-12 parent-group">
                            <textarea class="form-control base-plugin--summernote" disabled>{!! $record->consequence !!}</textarea>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="tanggapanAuditee" role="tabpanel" aria-labelledby="tanggapanAuditee-tab">
                    <div class="form-group row">
                        <label class="col-md-12 col-form-label">{{ __('Tanggapan Auditee') }}</label>
                        <div class="col-md-12 parent-group">
                            <textarea class="form-control base-plugin--summernote" disabled>{!! $record->feedback_note !!}</textarea>
                        </div>
                        <label class="col-md-12 col-form-label">{{ __('Status') }}</label>
                        <div class="col-md-12 parent-group">
                            <select class="form-control base-plugin--select2" disabled>
                                <option value="valid" @if ($record->feedback_status != 'invalid') selected @endif>Sependapat
                                </option>
                                <option value="invalid" @if ($record->feedback_status == 'invalid') selected @endif>Tidak Sependapat
                                </option>
                            </select>
                        </div>
                        {{-- <label class="col-md-12 col-form-label">{{ __('Lampiran') }}</label>
                        <div class="col-md-12 parent-group">
                            @foreach ($record->filesAuditee as $file)
                                @if ($file->module == 'conducting_feedback')
                                    <div class="progress-container w-100" data-uid="{{ $file->id }}">
                                        <div class="alert alert-custom alert-light fade show success-uploaded mb-0 mt-2 px-4 py-2"
                                            role="alert">
                                            <div class="alert-icon">
                                                <i class="{{ $file->file_icon }}"></i>
                                            </div>
                                            <div class="alert-text text-left">
                                                <input type="hidden" name="uploads[files_ids][]"
                                                    value="{{ $file->id }}">
                                                <div>Uploaded File:</div>
                                                <a href="{{ $file->file_url }}" target="_blank" class="text-primary">
                                                    {{ $file->file_name }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                            @if (!$record->filesAuditee()->exists())
                                <div>File tidak tersedia!</div>
                            @endif
                        </div> --}}
                    </div>
                </div>
                <div class="tab-pane fade" id="perbaikan" role="tabpanel" aria-labelledby="perbaikan-tab">
                    <div class="form-group row">
                        <label class="col-md-12 col-form-label">{{ __('Rencana Perbaikan') }}</label>
                        <div class="col-md-12 parent-group">
                            <textarea disabled name="perbaikan" class="base-plugin--summernote" placeholder="{{ __('Rencana Perbaikan') }}" data-height="150">{!! $record->perbaikan !!}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12 col-form-label">{{ __('Lampiran') }}</label>
                        <div class="col-md-12 parent-group">
                            @forelse ($record->filesAuditee as $file)
                            <div class="progress-container w-100" data-uid="{{ $file->id }}">
                                <div class="alert alert-custom alert-light fade show py-2 px-4 mb-0 mt-2 success-uploaded" role="alert">
                                    <div class="alert-icon">
                                        <i class="{{ $file->file_icon }}"></i>
                                    </div>
                                    <div class="alert-text text-left">
                                        <input type="hidden" name="uploads[files_ids][]" value="{{ $file->id }}">
                                        <div>Uploaded File:</div>
                                        <a href="{{ $file->file_url }}" target="_blank" class="text-primary">
                                            {{ $file->file_name }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @empty
                                <div>-</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="rekomendasi" role="tabpanel" aria-labelledby="rekomendasi-tab">
                    <div class="form-group row">
                        <div class="col-md-12 parent-group">
                            <textarea disabled class="form-control base-plugin--summernote" name="recommendation">{!! $record->recommendation !!}</textarea>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="reviewAuditor" role="tabpanel" aria-labelledby="reviewAuditor-tab">
                    <div class="form-group row">
                        <label class="col-md-12 col-form-label">{{ __('Review Auditor') }}</label>
                        <div class="col-md-12 parent-group">
                            <textarea class="form-control base-plugin--summernote" rows="5" disabled>{!! $record->review_note !!}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12 col-form-label">{{ __('Lampiran Review Auditor') }}</label>
                        <div class="col-md-12 parent-group">
                            @foreach ($record->filesAuditor as $file)
                                <div class="progress-container w-100" data-uid="{{ $file->id }}">
                                    <div class="alert alert-custom alert-light fade show success-uploaded mb-0 mt-2 px-4 py-2"
                                        role="alert">
                                        <div class="alert-icon">
                                            <i class="{{ $file->file_icon }}"></i>
                                        </div>
                                        <div class="alert-text text-left">
                                            <input type="hidden" name="uploads[files_ids][]"
                                                value="{{ $file->id }}">
                                            <div>Uploaded File:</div>
                                            <a href="{{ $file->file_url }}" target="_blank" class="text-primary">
                                                {{ $file->file_name }}
                                            </a>
                                        </div>
                                        {{-- <div class="alert-close">
                                        <button type="button" class="close base-form--remove-temp-files" data-toggle="tooltip"
                                            data-original-title="Remove">
                                            <span aria-hidden="true">
                                                <i class="ki ki-close"></i>
                                            </span>
                                        </button>
                                    </div> --}}
                                    </div>
                                </div>
                            @endforeach
                            @if (!$record->filesAuditor()->exists())
                                <div>File tidak tersedia!</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="komitmen" role="tabpanel" aria-labelledby="komitmen-tab">
                    <div class="form-group row">
                        <div class="col-md-12 parent-group">
                            <textarea class="form-control base-plugin--summernote" disabled>{!! $record->regItem->commitment ?? '' !!}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <label class="col-form-label">{{ __('Jenis Temuan') }}</label>
            <div class="parent-group">
                <input type="text" class="form-control" value="{{ $record->regItem->show_repeated }}" disabled>
            </div>
        </div>
        <div class="col-sm-4">
            <label class="col-form-label">{{ __('Batas Waktu') }}</label>
            <div class="parent-group">
                <input type="text" class="form-control"
                    value="{{ $record->regItem->deadline ? $record->regItem->deadline->format('d/m/Y') : '' }}" disabled>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group row">
                <label class="col-md-12 col-form-label">{{ __('Tgl Penyelesasian') }}</label>
                <div class="col-md-12 parent-group">
                    @php
                        $options = [
                            'startDate' => now()->format('d/m/Y'),
                            'endDate' => '',
                        ];
                    @endphp
                    <input type="text" name="completion_date" class="form-control base-plugin--datepicker"
                        data-options='@json($options)'
                        value="{{ $record->regItem->completion_date ? $record->regItem->completion_date->format('d/m/Y') : '' }}"
                        placeholder="{{ __('Tgl Penyelesasian') }}" disabled>
                </div>
            </div>
        </div>
    </div>

    @if ($regItem = $record->regItem)
        <div class="form-group row">
            <label class="col-md-12 col-form-label">{{ __('Tindak Lanjut') }}</label>
            <div class="col-md-12 parent-group">
                <textarea class="form-control base-plugin--summernote" disabled>{!! $regItem->followup_note !!}</textarea>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-12 col-form-label">{{ __('Tipe') }}</label>
            <div class="col-md-12 parent-group">
                <input type="text" class="form-control" value="{{ $regItem->type == 'link' ? 'Link' : 'Upload' }}"
                    disabled>
            </div>
        </div>
        <div class="upload-container @if ($regItem->type != 'upload') d-none @endif">
            <div class="form-group row">
                <label class="col-md-12 col-form-label">{{ __('Lampiran') }}</label>
                <div class="col-md-12 parent-group">
                    @foreach ($regItem->files as $file)
                        <div class="progress-container w-100" data-uid="{{ $file->id }}">
                            <div class="alert alert-custom alert-light fade show success-uploaded mb-0 mt-2 px-4 py-2"
                                role="alert">
                                <div class="alert-icon">
                                    <i class="{{ $file->file_icon }}"></i>
                                </div>
                                <div class="alert-text text-left">
                                    <input type="hidden" name="uploads[files_ids][]" value="{{ $file->id }}">
                                    <div>Uploaded File:</div>
                                    <a href="{{ $file->file_url }}" target="_blank" class="text-primary">
                                        {{ $file->file_name }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @if (!$regItem->files()->exists())
                        <div>File tidak tersedia!</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="link-container @if ($regItem->type != 'link') d-none @endif">
            <div class="form-group row">
                <label class="col-md-12 col-form-label">{{ __('Link') }}</label>
                <div class="col-md-12 parent-group">
                    <textarea class="form-control" placeholder="Link" disabled>{!! $regItem->link !!}</textarea>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('buttons')
@endsection

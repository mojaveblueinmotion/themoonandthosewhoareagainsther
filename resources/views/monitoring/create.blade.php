@extends('layouts.form', ['container' => 'container'])

@section('action', rut($routes . '.storeNew', $summary->id))

@section('card-body')
    @method('POST')
    @include('globals.header')
    <hr>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
                <label class="col-md-4 col-form-label">{{ __('Tanggal Surat') }}</label>
                <div class="col-md-8 parent-group">
                    <input type="text" name="letter_date" class="form-control base-plugin--datepicker letter_date"
                        placeholder="{{ __('Tanggal Surat') }}" value="" data-orientation="bottom"
                        data-options='@json([
                            'startDate' => $summary->assignmentLetterDate('startDate'),
                            'endDate' => $summary->assignmentLetterDate('endDate'),
                        ])' autocomplete="off">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-4 col-form-label">{{ __('Kepada') }}</label>
                <div class="col-md-8 parent-group">
                    <select name="users[]" class="form-control base-plugin--select2-ajax"
                        data-url="{{ rut('ajax.selectUser', [
                            'search' => 'assignment',
                            'summary_id' => $summary->id,
                        ]) }}"
                        multiple placeholder="{{ __('Pilih Beberapa') }}">
                        <option value="">{{ __('Pilih Beberapa') }}</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row">
                <label class="col-md-4 col-form-label">{{ __('No. Surat') }}</label>
                <div class="col-md-8 parent-group">
                    @if (config('base.company.key') == 'patriot')
                        <input type="text" class="form-control" name="letter_manual" placeholder="{{ __('No. Surat') }}">

                        <div class="d-none">
                            <input type="hidden" name="letter_id" value="{{ $letter->id }}">
                            <input type="text" class="form-control letter_no" value="{{ $letter->formated }}" readonly
                                data-no="{{ $letter->no }}" data-format="{{ $letter->format }}">
                        </div>
                    @else
                        <input type="hidden" name="letter_id" value="{{ $letter->id }}">
                        <input type="text" class="form-control letter_no" value="{{ $letter->formated }}" readonly
                            data-no="{{ $letter->no }}" data-format="{{ $letter->format }}">
                    @endif
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-4 col-form-label">{{ __('Alamat') }}</label>
                <div class="col-md-8 parent-group">
                    <textarea name="to_address" class="form-control" placeholder="{{ __('Alamat') }}">{!! $summary->assignmentDefaultAddress() !!}</textarea>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="form-group row">
        <label class="col-md-2 col-form-label">{{ __('Deskripsi') }}</label>
        <div class="col-md-10 parent-group">
            <textarea name="description" class="form-control" placeholder="{{ __('Deskripsi') }}">{!! $summary->assignmentDefaultDescription() !!}</textarea>
        </div>
    </div>
    <hr>
    <div class="form-group row">
        <label class="col-md-2 col-form-label">{{ __('Penanggung Jawab') }}</label>
        <div class="col-md-10 parent-group">
            <select name="pic_id" class="form-control base-plugin--select2-ajax"
                data-url="{{ rut('ajax.selectUser', 'auditor') }}" placeholder="{{ __('Pilih Salah Satu') }}">
                <option value="">{{ __('Pilih Salah Satu') }}</option>
                @if ($val = $summary->assignment->pic ?? $summary->pic)
                    <option value="{{ $val->id }}" selected>{{ $val->name }} ({{ $val->position->name }})
                    </option>
                @endif
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-2 col-form-label">{{ __('Ketua Tim') }}</label>
        <div class="col-md-10 parent-group">
            <select name="leader_id" class="form-control base-plugin--select2-ajax"
                data-url="{{ rut('ajax.selectUser', 'auditor') }}" placeholder="{{ __('Pilih Salah Satu') }}">
                <option value="">{{ __('Pilih Salah Satu') }}</option>
                @if ($val = $summary->assignment->leader ?? $summary->leader)
                    <option value="{{ $val->id }}" selected>{{ $val->name }} ({{ $val->position->name }})
                    </option>
                @endif
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-2 col-form-label">{{ __('Anggota Tim') }}</label>
        <div class="col-md-10 parent-group">
            <select name="members[]" class="form-control base-plugin--select2-ajax"
                data-url="{{ rut('ajax.selectUser', 'auditor') }}" multiple placeholder="{{ __('Pilih Beberapa') }}">
                <option value="">{{ __('Pilih Beberapa') }}</option>
                @foreach ($summary->assignment->members ?? $summary->members as $val)
                    <option value="{{ $val->id }}" selected>{{ $val->name }} ({{ $val->position->name }})
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <hr>
    <div class="form-group row">
        <label class="col-md-2 col-form-label">{{ __('Keterangan') }}</label>
        <div class="col-md-10 parent-group">
            <textarea name="note" class="form-control" placeholder="{{ __('Keterangan') }}">{!! $summary->assignmentDefaultNote() !!}</textarea>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-2 col-form-label">{{ __('Tgl. Pelaksanaan') }}</label>
        <div class="col-md-10 parent-group">
            <div class="input-group">
                <input type="text" name="date_start" class="form-control base-plugin--datepicker date_start"
                    placeholder="{{ __('Mulai') }}" data-orientation="top" data-options='@json([
                        'startDate' => $summary->assignmentDateStart('startDate'),
                        'endDate' => $summary->assignmentDateStart('endDate'),
                    ])'>
                <div class="input-group-append">
                    <span class="input-group-text">
                        <i class="la la-ellipsis-h"></i>
                    </span>
                </div>
                <input type="text" name="date_end" class="form-control base-plugin--datepicker date_end"
                    placeholder="{{ __('Selesai') }}" data-orientation="top" data-options='@json([
                        'startDate' => $summary->assignmentDateStart('startDate'),
                        'endDate' => $summary->assignmentDateStart('endDate'),
                    ])'
                    disabled>
            </div>
        </div>
    </div>
    <hr>
    <div class="form-group row">
        <label class="col-md-2 col-form-label">{{ __('Lingkup Audit') }}</label>
        <div class="col-md-10 parent-group">
            <select name="aspects[]" class="form-control base-plugin--select2-ajax"
                data-url="{{ rut('ajax.selectAspect', [
                    'search' => 'by_object',
                    'category' => $summary->getCategory(true),
                    'object_id' => $summary->getObjectId(),
                ]) }}"
                multiple placeholder="{{ __('Pilih Beberapa') }}">
                <option value="">{{ __('Pilih Beberapa') }}</option>
            </select>
        </div>
    </div>
@endsection

@section('card-footer')
    <div class="d-flex justify-content-between">
        @include('layouts.forms.btnBack')
        @include('layouts.forms.btnDropdownSubmit')
    </div>
@endsection

@push('scripts')
    @include($views . '.includes.scripts')
@endpush

@extends('layouts.modal')

@section('modal-body')
    <div class="form-group row">
        <label class="col-md-4 col-form-label">{{ __('Tahun') }}</label>
        <div class="col-md-8 parent-group">
            <input type="text" value="{{ $record->year }}" class="form-control" disabled>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-4 col-form-label">{{ __('Jenis Audit') }}</label>
        <div class="col-md-8 parent-group">
            <input type="text" value="{{ $record->type->name ?? '' }}" class="form-control" disabled>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-4 col-form-label">{{ __('Subject Audit') }}</label>
        <div class="col-md-8 parent-group">
            <input type="text" value="{{ $record->subject->name ?? '' }}" class="form-control" disabled>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Dept Auditee') }}</label>
        <div class="col-sm-8 parent-group">
            <select disabled class="form-control base-plugin--select2-ajax unitKerja" id="unitKerja"
                data-url="{{ rut('ajax.selectStruct', 'subject') }}" multiple>
                @foreach ($summary->departmentAuditee->departments as $val)
                    <option selected>{{ $val->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-4 col-form-label">{{ __('No LHA') }}</label>
        <div class="col-md-8 parent-group">
            <input disabled name="code" class="form-control" placeholder="{{ __('No LHA') }}"
                value="{{ $record->code }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-4 col-form-label">{{ __('Tanggal LHA') }}</label>
        <div class="col-md-8 parent-group">
            <input disabled name="date" class="form-control base-plugin--datepicker"
                placeholder="{{ __('Tanggal LHA') }}" value="{{ $record->date->format('d/m/Y') }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Lampiran') }}</label>
        <div class="col-sm-8 parent-group">
            @forelse ($record->files($module)->where('flag', 'attachments')->get() as $file)
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
@endsection

@section('buttons')
@endsection

@push('scripts')
    <script>
        $(function() {
            $('.modal-dialog-right-bottom').removeClass('modal-md').addClass('modal-lg');
        });
    </script>
@endpush

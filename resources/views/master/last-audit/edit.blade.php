@extends('layouts.modal')

@section('action', route($routes . '.update', $record->id))

@section('modal-body')
    @method('PATCH')
    <input type="hidden" id="departmentAuditeeCtrl" name="unitKerja" value="{{ $record->deptAuditee->id }}">
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Tahun') }}</label>
        <div class="col-sm-8 parent-group">
            <input readonly type="text" name="year" class="form-control base-plugin--datepicker-3"
                data-orientation="bottom" value="{{ $record->year }}" placeholder="{{ __('Tahun') }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Jenis Audit') }}</label>
        <div class="col-sm-8 parent-group">
            <input type="hidden" name="type_id" value="{{ $record->type->id }}" type="hidden">
            <select name="type_id" data-post="type_id" class="form-control filter-control base-plugin--select2-ajax type_id"
                data-url="{{ rut('ajax.selectTypeAudit', ['search' => 'all']) }}"
                placeholder="{{ __('Pilih Salah Satu ') }}" disabled>
                <option value="">{{ __('Jenis Audit') }}</option>
                @if ($record->subject)
                    <option selected>{{ $record->type->name }}</option>
                @endif
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-4 col-form-label">{{ __('Subject Audit') }}</label>
        <div class="col-md-8 parent-group">
            <input type="hidden" name="object_id" value="{{ $record->object_id }}" type="hidden">
            <select name="object_id" id="subject_id" class="form-control base-plugin--select2-ajax subject_id"
                data-placeholder="{{ __('Pilih Salah Satu') }}" disabled
                data-url="{{ rut('ajax.selectStruct', ['search' => 'subject', 'type_id' => $record->type_id]) }}">
                @if (!empty($record->subject))
                    <option value="{{ $record->subject->id }}" selected>{{ $record->subject->name }}</option>
                @endif
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Dept Auditee') }}</label>
        <div class="col-sm-8 parent-group">
            <input type="hidden" name="auditee_id" value="{{ $record->auditee_id }}" id="auditee_id">
            <select disabled class="form-control base-plugin--select2-ajax unitKerja" id="unitKerja"
                data-url="{{ rut('ajax.selectStruct', 'subject') }}" multiple>
                @foreach ($record->deptAuditee->departments as $val)
                    <option selected>{{ $val->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('No LHA') }}</label>
        <div class="col-sm-8 parent-group">
            <input name="code" class="form-control" placeholder="{{ __('No LHA') }}" value="{{ $record->code }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Tanggal LHA') }}</label>
        <div class="col-sm-8 parent-group">
            <input name="date" class="form-control base-plugin--datepicker" data-options='@json([
                'startDate' => '',
                'endDate' => now()->format('d/m/Y'),
            ])'
                placeholder="{{ __('Tanggal LHA') }}" value="{{ $record->date->format('d/m/Y') }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Lampiran') }}</label>
        <div class="col-sm-8 parent-group">
            <div class="custom-file">
                <input type="hidden" name="attachments[uploaded]" class="uploaded" value="0">
                <input type="file" multiple class="custom-file-input base-form--save-temp-files" data-name="attachments"
                    data-container="parent-group" data-max-size="20024" data-max-file="100" accept="*">
                <label class="custom-file-label" for="file">Choose File</label>
            </div>
            <div class="form-text text-muted">*Maksimal 20MB</div>
            @foreach ($record->files($module)->where('flag', 'attachments')->get() as $file)
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
                        <div class="alert-close">
                            <button type="button" class="close base-form--remove-temp-files" data-toggle="tooltip"
                                data-original-title="Remove">
                                <span aria-hidden="true">
                                    <i class="ki ki-close"></i>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('.modal-dialog-right-bottom').removeClass('modal-md').addClass('modal-lg');

            $('.content-page').on('change', 'select.type_id', function(e) {
                var me = $(this);
                if (me.val() == 2) {
                    $('#opspec_object').css("display", "none");
                    $('#object_type').css("display", "none");
                    $('#aspect_object').css("display", "");
                    $('#aspect_griya').css("display", "none");

                    if (me.val()) {
                        var objectId = $('select.object_id');
                        var urlOrigin = objectId.data('url-origin');
                        var urlParam = $.param({
                            category: 'provider'
                        });
                        objectId.data('url', decodeURIComponent(decodeURIComponent(urlOrigin + '?' +
                            urlParam)));
                        objectId.val(null).prop('disabled', false);
                    }
                } else {
                    $('#opspec_object').css("display", "");
                    $('#object_type').css("display", "");
                }

                BasePlugin.initSelect2();
            });

            $('.content-page').on('change', 'select.object_type', function(e) {
                var me = $(this);
                $('#aspect_griya').css("display", "none");
                $('#aspect_object').css("display", "");
                if (me.val()) {
                    var objectId = $('select.object_id');
                    var urlOrigin = objectId.data('url-origin');
                    var urlParam = $.param({
                        object_type: me.val()
                    });
                    objectId.data('url', decodeURIComponent(decodeURIComponent(urlOrigin + '?' +
                        urlParam)));
                    objectId.val(null).prop('disabled', false);
                }

                BasePlugin.initSelect2();
            });
        });
    </script>
@endpush

@extends('layouts.modal')

@section('action', rut($routes . '.store'))

@section('modal-body')
    @method('POST')
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">{{ __('Jenis Audit') }}</label>
        <div class="col-sm-9 parent-group">
            <select data-post="type_id" name="type_id" class="form-control filter-control base-plugin--select2-ajax type_id"
                data-url="{{ rut('ajax.selectTypeAudit', ['search' => 'all']) }}"
                data-url-origin="{{ rut('ajax.selectTypeAudit', ['search' => 'all']) }}"
                placeholder="{{ __('Pilih Salah Satu ') }}">
                <option value="">{{ __('Jenis ') }}</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">{{ __('Subject Audit') }}</label>
        <div class="col-sm-9 parent-group">
            <select name="subject_id" id="subjectAudit" class="form-control base-plugin--select2-ajax subjectAudit"
                data-url="{{ rut('ajax.selectStruct', ['search' => 'subject', 'type_id' => '']) }}"
                data-url-origin="{{ rut('ajax.selectStruct', 'subject') }}" disabled
                placeholder="{{ __('Pilih Salah Satu') }}">
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">{{ __('Nama') }}</label>
        <div class="col-9 parent-group">
            <input type="text" name="name" id="nameCtrl" class="form-control" placeholder="{{ __('Nama') }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">{{ __('Deskripsi') }}</label>
        <div class="col-9 parent-group">
            <textarea name="description" class="form-control" placeholder="{{ __('Deskripsi') }}"></textarea>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('.content-page').on('change', 'select.type_id', function(e) {
                var me = $(this);
                if (me.val()) {
                    var subjectId = $('select.subjectAudit')
                    var unitKerjaId = $('select.unitKerja');
                    var urlOrigin = subjectId.data('url-origin');
                    var urlParam = $.param({
                        search: 'subject',
                        type_id: me.val(),
                    });
                    subjectId.data('url', decodeURIComponent(decodeURIComponent(urlOrigin + '?' +
                        urlParam)));
                    subjectId.val(null).prop('disabled', false);
                    unitKerjaId.val(null).prop('disabled', true);
                }
                BasePlugin.initSelect2();
            });
        });
    </script>
@endpush

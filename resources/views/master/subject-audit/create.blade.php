@extends('layouts.modal')

@section('action', rut($routes . '.store'))

@section('modal-body')
    @method('POST')
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Kode') }}</label>
        <div class="col-sm-8 parent-group">
            <input name="code" class="form-control" id="codeCtrl" maxlength="3" placeholder="{{ __('Kode') }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Jenis Audit') }}</label>
        <div class="col-sm-8 parent-group">
            <select name="type_id" data-post="type_id"
                class="form-control filter-control base-plugin--select2-ajax filter-type-id"
                data-url="{{ rut('ajax.selectTypeAudit', ['search' => 'all']) }}"
                placeholder="{{ __('Pilih Salah Satu ') }}">
                <option value="">{{ __('Jenis Audit') }}</option>
            </select>
        </div>
    </div>
    {{-- <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Dept Auditee') }}</label>
        <div class="col-sm-8 parent-group">
            <select name="children[]" id="object_id" class="form-control base-plugin--select2-ajax object_id"
                data-url="{{ route('ajax.selectStruct', 'unit_kerja') }}"
                data-url-origin="{{ route('ajax.selectStruct', 'unit_kerja') }}"
                data-placeholder="{{ __('Pilih beberapa') }}" multiple>
            </select>
        </div>
    </div> --}}
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Nama') }}</label>
        <div class="col-sm-8 parent-group">
            <input id="nameCtrl" name="name" class="form-control" placeholder="{{ __('Nama') }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Deskripsi') }}</label>
        <div class="col-sm-8 parent-group">
            <textarea id="descCtrl" name="description" class="form-control" placeholder="{{ __('Deskripsi') }}"></textarea>
        </div>
    </div>
@endsection

@push('scripts')
    @include($views . '.includes.scripts ')
    <script>
        $(function() {
            $(".masking-code").inputmask({
                "mask": "9",
                "repeat": 3,
                "greedy": false
            });
            $('.content-page').on('change', 'select.type_id', function(e) {
                    var me = $(this);
                    if (me.val()) {
                        var subjectId = $('select.object_id')
                        var urlOrigin = subjectId.data('url-origin');
                        var urlParam = $.param({
                            search: 'subject',
                            type_id: me.val(),
                        });
                        subjectId.data('url', decodeURIComponent(decodeURIComponent(urlOrigin + '?' +
                            urlParam)));
                        subjectId.val(null).prop('disabled', false);
                    }
                    BasePlugin.initSelect2();
                })

                .on('change', 'select.object_type', function(e) {
                    var me = $(this);
                    if (me.val()) {
                        var objectId = $('select.object_id');
                        var urlOrigin = objectId.data('url-origin');
                        var urlParam = $.param({
                            parent_id: me.val()
                        });
                        objectId.data('url', decodeURIComponent(decodeURIComponent(urlOrigin + '?' +
                            urlParam)));
                        objectId.val(null).prop('disabled', false);

                    }
                    BasePlugin.initSelect2();
                });

            $('.content-page').on('change', 'select.object_id', function(e) {
                var me = $(this);
                if (me.val()) {
                    var ctrlAuditor = $('select.ctrlAuditor');
                    var typeId = $('select.type_id');
                    var urlOrigin = ctrlAuditor.data('url-origin');
                    var urlParam = $.param({
                        search: '',

                    });
                    console.log(urlParam);
                    // ctrlAuditor.data('url', decodeURIComponent(decodeURIComponent(urlOrigin + '?' +
                    //     urlParam)));
                    ctrlAuditor.val(null).prop('disabled', false);

                }
                BasePlugin.initSelect2();
            });


        });
    </script>
@endpush

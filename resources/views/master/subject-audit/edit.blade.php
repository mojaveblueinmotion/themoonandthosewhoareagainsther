@extends('layouts.modal')

@section('action', route($routes . '.update', $record->id))

@section('modal-body')
    @method('PATCH')
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Kode') }}</label>
        <div class="col-sm-8 parent-group">
            <input name="code" class="form-control" value="{{ $record->code }}" placeholder="{{ __('Kode') }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Jenis Audit') }}</label>
        <div class="col-sm-8 parent-group">
            <select name="type_id" data-post="type_id"
                class="form-control filter-control base-plugin--select2-ajax filter-type-id"
                data-url="{{ rut('ajax.selectTypeAudit', ['search' => 'all']) }}"
                placeholder="{{ __('Pilih Salah Satu ') }}">
                <option value="{{ $record->typeAudit->id }}">{{ $record->typeAudit->name }}</option>

            </select>
        </div>
    </div>
    {{-- <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Dept Auditee') }}</label>
        <div class="col-sm-8 parent-group">
            @foreach ($record->childOfGroup as $item)
                <input type="hidden" name="children[]" value="{{ $item->id }}">
            @endforeach
            <select class="form-control base-plugin--select2-ajax object_id"
                data-url="{{ route('ajax.selectStruct', ['search' => 'unit_kerja']) }}"
                data-url-origin="{{ route('ajax.selectStruct', 'unit_kerja') }}"
                data-placeholder="{{ __('Pilih Salah Satu') }}" disabled multiple>
                @foreach ($record->childOfGroup as $item)
                    <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
    </div> --}}
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Nama') }}</label>
        <div class="col-sm-8 parent-group">
            <input id="nameCtrl" name="name" class="form-control" placeholder="{{ __('Nama') }}"
                value="{{ $record->name }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Deskripsi') }}</label>
        <div class="col-sm-8 parent-group">
            <textarea name="description" id="descCtrl" class="form-control" placeholder="{{ __('Deskripsi') }}">{{ $record->description }}</textarea>
        </div>
    </div>
@endsection

@push('scripts')
    @include($views . '.includes.scripts ')
    <script>
        $(function() {
            $('.content-page')

                .on('change', 'select.object_type', function(e) {
                    var me = $(this);
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
                })
                .on('change', 'select.object_id', function(e) {
                    var me = $(this);
                    if (me.val()) {
                        var ctrlAuditor = $('select.ctrlAuditor');
                        var typeId = $('select.type_id');
                        var urlOrigin = ctrlAuditor.data('url-origin');
                        var urlParam = $.param({
                            search: 'object_unit',
                            type_id: typeId.val(),
                            location_id: me.val()
                        });
                        console.log(urlParam);
                        ctrlAuditor.val(null).prop('disabled', false);

                    }
                    BasePlugin.initSelect2();
                })
                .on('change', 'select.object_type', function(e) {
                    var me = $(this);
                    if (me.val()) {
                        var ctrlAuditor = $('select.ctrlAuditor');
                        var typeId = $('select.type_id');
                        var urlOrigin = ctrlAuditor.data('url-origin');
                        var urlParam = $.param({
                            search: 'object_unit',

                        });
                        console.log(urlParam);
                        ctrlAuditor.val(null).prop('disabled', false);

                    }
                    BasePlugin.initSelect2();
                });;
        });
    </script>
@endpush

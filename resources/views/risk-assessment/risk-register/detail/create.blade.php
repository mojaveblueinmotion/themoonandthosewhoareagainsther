@extends('layouts.modal')

@section('action', route($routes . '.detailStore', $record->id))

@section('modal-body')
    @method('POST')
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">{{ __('ID Resiko') }}</label>
        <div class="col-sm-9 parent-group">
            <input type="text" name="id_resiko" class="form-control" readonly value="{{ $record->generateIdResiko() }}"
                placeholder="{{ __('ID Resiko') }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">{{ __('Main Process') }}</label>
        <input hidden type="text" name="risk_register_id" value="{{ $record->id }}">
        <div class="col-9 parent-group">
            <select name="main_process_id" class="form-control base-plugin--select2-ajax main_process_id"
                data-url="{{ route('ajax.selectMainProcess', ['subject_id' => $record->object_id]) }}"
                placeholder="{{ __('Pilih Salah Satu') }}">
                <option value="">{{ __('Pilih Salah Satu') }}</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">{{ __('Sub Process') }}</label>
        <div class="col-9 parent-group">
            <select name="sub_process_id" class="form-control base-plugin--select2-ajax sub_process_id_detail"
                data-url="{{ route('ajax.selectAspect', ['search' => 'by_main_process', 'main_process_id' => '']) }}"
                data-url-origin="{{ rut('ajax.selectAspect', ['search' => 'by_main_process']) }}" disabled
                placeholder="{{ __('Pilih Salah Satu') }}">
                <option value="">{{ __('Pilih Salah Satu') }}</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">{{ __('Proses Objective') }}</label>
        <div class="col-9 parent-group">
            <textarea required name="objective" class="form-control" placeholder="{{ __('Proses Objective') }}"></textarea>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">{{ __('Risk Event') }}</label>
        <div class="col-9 parent-group">
            <textarea required name="peristiwa" class="form-control" placeholder="{{ __('Risk Event') }}"></textarea>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">{{ __('Risk Cause') }}</label>
        <div class="col-9 parent-group">
            <textarea required name="penyebab" class="form-control" placeholder="{{ __('Risk Cause') }}"></textarea>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">{{ __('Risk Impact') }}</label>
        <div class="col-9 parent-group">
            <textarea required name="dampak" class="form-control" placeholder="{{ __('Risk Impact') }}"></textarea>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('.modal-dialog').removeClass('modal-md').addClass('modal-lg');
        $('.content-page').on('change', 'select.main_process_id', function(e) {
            var me = $(this);
            if (me.val()) {
                var subProcess = $('select.sub_process_id_detail');
                var urlOrigin = subProcess.data('url-origin');
                console.log(subProcess);
                var urlParam = $.param({
                    main_process_id: me.val(),
                });
                console.log(urlOrigin + '?' +
                    urlParam)
                subProcess.data('url', decodeURIComponent(decodeURIComponent(urlOrigin + '?' +
                    urlParam)));
                subProcess.val(null).prop('disabled', false);
            }
            BasePlugin.initSelect2();
        });
    </script>
@endpush

@extends('layouts.modal')

@section('action', rut($routes . '.update', $record->id))

@section('modal-body')
    @method('PATCH')
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">{{ __('Nama') }}</label>
        <div class="col-sm-9 parent-group">
            <input type="text" name="name" value="{{ $record->name }}" class="form-control"
                placeholder="{{ __('Nama') }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">{{ __('Email') }}</label>
        <div class="col-sm-9 parent-group">
            <input type="text" name="email" value="{{ $record->email }}" class="form-control"
                placeholder="{{ __('Email') }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">{{ __('Website') }}</label>
        <div class="col-sm-9 parent-group">
            <input type="text" name="website" value="{{ $record->website }}" class="form-control"
                placeholder="{{ __('Website') }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">{{ __('Telepon') }}</label>
        <div class="col-sm-9 parent-group">
            <input type="text" name="phone" value="{{ $record->phone }}" class="form-control"
                placeholder="{{ __('Telepon') }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">{{ __('Alamat') }}</label>
        <div class="col-sm-9 parent-group">
            <textarea type="text" name="address" class="form-control" placeholder="{{ __('Address') }}">{{ $record->address }}</textarea>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 col-form-label">{{ __('Provinsi') }}</label>
        <div class="col-md-9 parent-group">
            <select class="form-control base-plugin--select2-ajax province_id"
                data-url="{{ rut('ajax.selectProvince', [
                    'search' => 'all',
                ]) }}"
                data-url-origin="{{ rut('ajax.selectProvince', [
                    'search' => 'all',
                ]) }}"
                placeholder="{{ __('Pilih Salah Satu') }}" required>
                <option value="">{{ __('Pilih Salah Satu') }}</option>
                @if (!empty($record->city_id))
                    <option value="{{ $record->city->province_id }}" selected>{{ $record->city->province->name }}</option>
                @endif
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 col-form-label">{{ __('Kota') }}</label>
        <div class="col-md-9 parent-group">
            <select name="city_id" class="form-control base-plugin--select2-ajax city_id"
                data-url="{{ rut('ajax.cityOptions', ['province_id' => '']) }}"
                data-url-origin="{{ rut('ajax.cityOptionsRoot') }}" placeholder="{{ __('Pilih Salah Satu') }}" disabled
                required>
                <option value="">{{ __('Pilih Salah Satu') }}</option>
                @if (!empty($record->city_id))
                    <option value="{{ $record->city_id }}" selected>{{ $record->city->name }}</option>
                @endif
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">{{ __('Logo') }}</label>
        <div class="col-9 parent-group">
            <div class="custom-file">
                <input type="hidden" name="perseroan[uploaded]" class="uploaded" value="">
                <input type="file" class="custom-file-input base-form--save-temp-files" data-name="perseroan"
                    data-container="parent-group" data-max-size="2048" data-max-file="1" accept=".png">
                <label class="custom-file-label" for="file">Choose File</label>
            </div>
            @foreach ($record->files as $file)
                <div class="progress-container w-100" data-uid="{{ $file->id }}">
                    <div class="alert alert-custom alert-light fade show success-uploaded mb-0 mt-2 px-4 py-2"
                        role="alert">
                        <div class="alert-icon">
                            <i class="{{ $file->file_icon }}"></i>
                        </div>
                        <div class="alert-text text-left">
                            <input type="hidden" name="perseroan[files_ids][]" value="{{ $file->id }}">
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
            $('.content-page').on('change', 'select.province_id', function(e) {
                var me = $(this);
                if (me.val()) {
                    var objectId = $('select.city_id');
                    var urlOrigin = objectId.data('url-origin');
                    var urlParam = $.param({
                        province_id: me.val()
                    });
                    objectId.data('url', decodeURIComponent(decodeURIComponent(urlOrigin + '?' +
                        urlParam)));
                    objectId.val(null).prop('disabled', false);
                }
                BasePlugin.initSelect2();
            });
        });
        $('.modal-dialog').removeClass('modal-md').addClass('modal-lg');
    </script>
@endpush

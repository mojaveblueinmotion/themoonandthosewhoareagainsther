@extends('layouts.page')

@section('page')
    <div class="d-flex flex-row">
        @include($views . '.includes.profile-aside', ['tab' => 'profile'])

        <div class="flex-row-fluid ml-lg-8">
            <div class="card card-custom gutter-b">

                <div class="card-header py-3">
                    <div class="card-title align-items-start flex-column">
                        <h3 class="card-label font-weight-bolder text-dark">{{ __('Profil') }}</h3>
                        <span class="text-muted font-weight-bold font-size-sm mt-1">{{ __('Informasi Pribadi') }}</span>
                    </div>
                </div>

                <form action="{{ rut($routes . '.updateProfile') }}" method="post" autocomplete="off">
                    @csrf
                    @method('POST')

                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label text-right">{{ __('Foto') }}</label>
                            <div class="col-lg-9 col-xl-6">
                                <div class="image-input image-input-outline" id="kt_profile_avatar"
                                    style="background-image: url({{ asset('assets/media/users/blank.png') }})">
                                    <img class="image-input-wrapper show"
                                        style="background-image: url({{ asset(auth()->user()->image_path) }})">
                                    <label
                                        class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                        data-action="change" data-toggle="tooltip" title=""
                                        data-original-title="Ganti foto">
                                        <i class="fa fa-pen icon-sm text-muted"></i>
                                        <input type="file" id="srcfile" name="image" accept=".png, .jpg, .jpeg">
                                        <input type="hidden" name="image">
                                    </label>
                                    <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                        data-action="cancel" data-toggle="tooltip" title=""
                                        data-original-title="Cancel avatar">
                                        <i class="ki ki-bold-close icon-xs text-muted"></i>
                                    </span>
                                </div>
                                <span class="form-text text-muted">Allowed file types: png, jpg, jpeg.</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label text-right">{{ __('Nama') }}</label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control form-control-lg form-control-solid" type="text"
                                    value="{{ auth()->user()->name }}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label text-right">{{ __('NIP') }}</label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control form-control-lg form-control-solid" type="text"
                                    value="{{ auth()->user()->npp ?? '-' }}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label text-right">{{ __('Struktur') }}</label>
                            <div class="col-lg-9 col-xl-6">
                                @if (auth()->user()->id == 1)
                                    <input type="text" value=""
                                        class="form-control form-control-lg form-control-solid" disabled>
                                @else
                                    <input type="text"
                                        value="{{ auth()->user()->position->location->name ?? auth()->user()->provider->name }}"
                                        class="form-control" disabled>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label text-right">{{ __('Level Jabatan') }}</label>
                            <div class="col-lg-9 col-xl-6">
                                <input type="text"
                                    value="{{ isset(auth()->user()->position) ? auth()->user()->position->level->name : '' }}"
                                    class="form-control" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label text-right">{{ __('Jabatan') }}</label>
                            <div class="col-lg-9 col-xl-6">
                                <input type="text"
                                    value="{{ auth()->user()->position->name ?? auth()->user()->jabatan_provider }}"
                                    class="form-control" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label text-right">{{ __('Hak Akses') }}</label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control form-control-lg form-control-solid" type="text"
                                    value="{{ auth()->user()->roles_imploded }}" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-xl-3"></label>
                            <div class="col-lg-9 col-xl-6">
                                <h5 class="font-weight-bold mb-6 mt-10">{{ __('Informasi Kontak') }}</h5>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label text-right">{{ __('Telepon') }}</label>
                            <div class="col-lg-9 col-xl-6">
                                <div class="input-group input-group-lg input-group-solid">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="la la-phone"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control form-control-lg form-control-solid"
                                        name="phone" value="{{ auth()->user()->phone }}"
                                        placeholder="{{ __('Telepon') }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label text-right">{{ __('Email') }}</label>
                            <div class="col-lg-9 col-xl-6">
                                <div class="input-group input-group-lg input-group-solid">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="la la-at"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control form-control-lg form-control-solid"
                                        name="email" value="{{ auth()->user()->email }}"
                                        placeholder="{{ __('Email') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-right">
                        <button type="submit" data-swal-confirm="false" class="btn btn-primary base-form--submit-page">
                            <i class="fa fa-save mr-1"></i>
                            {{ __('Simpan') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        $('form button').on('click', function(e) {
            var url_image_user = $('.show').attr('src');
            console.log(url_image_user);
            $('#ctrlProfilePhoto').css('display', '');
            $('#ctrlProfilePhotoDefault').css('display', 'none');
            $('.profileImage').attr('src', url_image_user);
        });
    </script>
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('.show').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]); // convert to base64 string
            }
        }

        $("#srcfile").change(function() {
            readURL(this);
        });
    </script>
@endpush

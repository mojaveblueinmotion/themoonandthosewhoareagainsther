@if (request()->header('Base-Replace-Content'))
    <script>
        window.location.reload();
    </script>
@else
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <title>{{ !empty($title) ? $title . ' | ' . config('app.name') : config('app.name') }}</title>
        <meta name="debug" content="{{ config('app.debug') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="base-url" content="{{ url('/') }}">
        <meta name="replace" content="1">
        <meta name="author" content="Tunas Mekar" />
        <meta name="description" content="Tunas Mekar" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

        <link rel="shortcut icon" href="{{ '/' . 'base.logo.favicon' }}" />
        <link rel="stylesheet" href="{{ '/assets/css/fonts/poppins/all.css' }}">
        <link rel="stylesheet" href="{{ '/assets/css/plugins.bundle.css' }}">
        <link rel="stylesheet" href="{{ '/assets/css/theme.bundle.css' }}">
        <link rel="stylesheet" href="{{ '/assets/css/theme.skins.bundle.css' }}">
        <link rel="stylesheet" href="{{ '/assets/css/base.bundle.css' }}">
        <link rel="stylesheet" href="{{ '/assets/css/modules.bundle.css' }}">
        <style>
            .patern-box {
                position: relative;
                width: 100%;
                background-image: linear-gradient(rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.2)), url('{{ url('assets/media/logos/gmp-background.jpg') }}');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
            }
        </style>
    </head>

    <body id="kt_body" class="header-fixed header-mobile-fixed page-loading">
        <div class="no-body-clear page-loader page-loader-default fade-out">
            <div class="blockui">
                <span>Please wait...</span>
                <span>
                    <div class="spinner spinner-primary"></div>
                </span>
            </div>
        </div>
        <div class="d-flex flex-column flex-root align-items-center justify-content-center patern-box">
            {{-- <div class="patern-box"></div> --}}
            <div class="login login-1 login-signin-on d-flex flex-column flex-lg-row" id="kt_login">
                <div class="d-flex flex-column flex-row-fluid position-relative overflow-hidden p-7">
                    <div class="d-flex flex-column-fluid flex-center mt-30 mt-lg-0">
                        <div class="card rounded-xl shadow" style="background-color: rgba(255,255,255,0.6)">
                            <div class="card-body p-0" style="background-color: rgba(255,255,255,0.6)">
                                <div class="d-flex">
                                    <div class="p-10">
                                        @yield('content')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-column align-items-center p-5">
                        <div class="font-weight-bold order-sm-1 order-2 my-2 text-white">
                            {{ config('base.app.name') }} - {{ config('base.app.version') }}
                        </div>
                        <div class="d-flex order-sm-2 order-1 my-2 text-white">
                            © {{ config('base.app.copyright') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="{{ '/assets/js/plugins.bundle.js' }}"></script>
        {{-- <script src="{{ ('/assets/js/theme.config.js')) }}"></script> --}}
        <script src="{{ '/assets/js/theme.bundle.js' }}"></script>
        <script src="{{ '/assets/js/base.bundle.js' }}"></script>
        <script src="{{ '/assets/js/modules.bundle.js' }}"></script>
        @stack('scripts')
    </body>

    </html>
@endif

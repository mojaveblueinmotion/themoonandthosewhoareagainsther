<div class="col-md-6">
    <div class="card card-custom card-stretch gutter-b chart-login-wrapper">
        <div class="card-header h-auto py-3">
            <div class="card-title">
                <h3 class="card-label">
                    <span class="d-block text-dark font-weight-bolder">{{ __('Login Perhari') }}</span>
                </h3>
            </div>
            <br>
            <form id="login-filter-chart" action="{{ rut($routes . '.chartLogin') }}" class="form-inline"
                role="form">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="input-daterange input-group text-nowrap">
                            <div class="input-group-append" data-toggle="tooltip" title="Filter">
                                <span class="input-group-text">
                                    <i class="fa fa-filter"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control login__start" name="login__start"
                                value="{{ request()->login__start ?? date('d/m/Y') }}" style="">
                            <div class="input-group-append">
                                <span class="input-group-text">/</span>
                            </div>
                            <input disabled type="text" class="form-control login__end" name="login__end"
                                value="{{ request()->login__end ?? date('d/m/Y') }}" style="">
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <select name="login__perusahaan"
                            class="form-control base-plugin--select2-ajax login__perusahaan"
                            data-url="{{ rut('ajax.selectStruct', [
                                'search' => 'root',
                            ]) }}"
                            @if(!empty($user->perusahaan))
                            disabled
                            @endif
                            placeholder="{{ __('Perusahaan') }}">
                            @if(!empty($user->perusahaan))
                            <option value="{{ $user->perusahaan->id }}">{{ __($user->perusahaan->name) }}</option>
                            @endif
                        </select>
                    </div>
                    {{-- <div class="col-md-6 col-sm-12 mt-4">
                        <select name="login__object"
                            class="form-control base-plugin--select2-ajax login__object"
                                data-url="{{ rut('ajax.selectStruct', [
                                    'search' => 'object_audit',
                                    'optstart_id' => 'all',
                                ]) }}"
                                data-url-origin="{{ rut('ajax.selectStruct', 'object_audit') }}"
                                placeholder="{{ __('Objek Audit') }}">
                            <option value="">{{ __('Objek Audit') }}</option>
                        </select>
                    </div> --}}
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="chart-wrapper">
                <div id="login-chart">
                    <div class="d-flex h-100">
                        <div class="spinners m-auto my-auto">
                            <div class="spinner-grow text-success" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <div class="spinner-grow text-danger" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <div class="spinner-grow text-warning" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <style>
        .chart-login-wrapper .apexcharts-menu-item.exportSVG,
        .chart-login-wrapper .apexcharts-menu-item.exportCSV {
            display: none;
        }

        .chart-login-wrapper .apexcharts-title-text {
            white-space: normal;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(function() {
            var today = new Date();
            var day = String(today.getDate()).padStart(2, '0');
            var month = String(today.getMonth() + 1).padStart(2, '0');
            var year = today.getFullYear();

            var formattedDate = day + '/' + month + '/' + year;
            $('input.login__end').datepicker('setDate', formattedDate);

            iniFilterChartLogin();
            drawChartLogin();
        });

        var iniFilterChartLogin = function() {
            $('input.login__start').datepicker({
                    format: "dd/mm/yyyy",
                    viewMode: "days",
                    minViewMode: "days",
                    orientation: "bottom auto",
                    autoclose: true
                })
                .on('changeDate', function(value) {
                    $('input.login__end')
                        .datepicker('destroy')
                        .datepicker({
                            format: "dd/mm/yyyy",
                            viewMode: "days",
                            minViewMode: "days",
                            orientation: "bottom auto",
                            startDate: new Date(value.date.valueOf()),
                            autoclose: true,
                        })
                        .on('changeDate', function(selected) {
                            drawChartLogin();
                        });
                    $('input.login__end').val('').focus();
                });

            $('input.login__end').datepicker({
                    format: "dd/mm/yyyy",
                    viewMode: "days",
                    minViewMode: "days",
                    orientation: "bottom auto",
                    startDate: new Date($('.login__start').val()),
                    autoclose: true,
                }).prop('disabled', false)
                .on('changeDate', function(selected) {
                    drawChartLogin();
                });

            $('.content-page')
                .on('change', 'select.login__perusahaan, select.login__object', function() {
                if ($(this).is('.login__perusahaan')) {
                    var me = $(this);
                    if (me.val()) {
                        var struct = $('select.login__object');
                        var urlOrigin = struct.data('url-origin');
                        var urlParam = $.param({
                            root_id: me.val(),
                        });
                        struct.data('url', decodeURIComponent(decodeURIComponent(urlOrigin + '?' +
                            urlParam)));
                        struct.val(null).prop('disabled', false);
                        BasePlugin.initSelect2();
                    }
                }
                drawChartLogin();
            });
            // $('select.login__perusahaan').trigger('change');

        }

        var drawChartLogin = function() {
            setTimeout(() => {
                var filter = $('#login-filter-chart');

                $.ajax({
                    url: filter.attr('action'),
                    method: 'POST',
                    data: {
                        _token: BaseUtil.getToken(),
                        login__start: filter.find('.login__start').val(),
                        login__end: filter.find('.login__end').val(),
                        login__perusahaan: filter.find('.login__perusahaan').val(),
                        login__object: filter.find('.login__object').val(),
                    },
                    success: function(resp) {
                        $('.chart-login-wrapper .chart-wrapper').find(
                                '#login-chart')
                            .remove();
                        $('.chart-login-wrapper .chart-wrapper').html(
                            `<div id="login-chart"></div>`);
                        renderChartLogin(resp);
                    },
                    error: function(resp) {
                        console.log(resp);
                    }
                });
            }, 10);
        }

        var renderChartLogin = function(options = {}) {
            var element = document.getElementById('login-chart');

            var defaultsOptions = {
                title: {
                    text: options.title.text ?? 'User',
                    align: 'center',
                    style: {
                        fontSize: '18px',
                        fontWeight: '500',
                    },
                },
                series: options.series ?? [],
                chart: {
                    type: 'line',
                    height: '400px',
                    stacked: true,
                    toolbar: {
                        show: true,
                        tools: {
                            download: true,
                            selection: false,
                            zoom: false,
                            zoomin: false,
                            zoomout: false,
                            pan: false,
                            reset: false,
                            customIcons: []
                        },
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: ['30%'],
                        endingShape: 'rounded'
                    },
                },
                legend: {
                    position: 'top',
                    offsetY: 2
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: [4, 0, 0, 0, 0, 0],
                    curve: 'smooth'
                    // colors: ['transparent']
                },
                xaxis: {
                    categories: options.xaxis.categories ?? [],
                    axisBorder: {
                        show: false,
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        style: {
                            colors: KTApp.getSettings()['colors']['gray']['gray-500'],
                            fontSize: '12px',
                            fontFamily: KTApp.getSettings()['font-family']
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: KTApp.getSettings()['colors']['gray']['gray-500'],
                            fontSize: '12px',
                            fontFamily: KTApp.getSettings()['font-family']
                        }
                    }
                },
                fill: {
                    opacity: [0.3, 1, 1, 1, 1, 1],
                    gradient: {
                        inverseColors: false,
                        shade: 'light',
                        type: "vertical",
                    }
                },
                states: {
                    normal: {
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    },
                    hover: {
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    },
                    active: {
                        allowMultipleDataPointsSelection: false,
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    }
                },
                tooltip: {
                    style: {
                        fontSize: '12px',
                        fontFamily: KTApp.getSettings()['font-family']
                    },
                    y: {
                        formatter: function(val) {
                            return val + " User"
                        }
                    }
                },
                colors: [
                    // KTApp.getSettings()['colors']['theme']['base']['secondary'],
                    KTApp.getSettings()['colors']['theme']['base']['primary'],
                    KTApp.getSettings()['colors']['theme']['base']['success'],
                    KTApp.getSettings()['colors']['theme']['base']['danger'],
                ],
                grid: {
                    borderColor: KTApp.getSettings()['colors']['gray']['gray-200'],
                    strokeDashArray: 4,
                    yaxis: {
                        lines: {
                            show: true
                        }
                    }
                },
                noData: {
                    text: 'Loading...'
                }
            };

            var chart = new ApexCharts(element, defaultsOptions);
            chart.render();
        }
    </script>
@endpush

<div class="col-md-6">
    <div class="card card-custom card-stretch gutter-b chart-login-monthly-wrapper">
        <div class="card-header h-auto py-3">
            <div class="card-title">
                <h3 class="card-label">
                    <span class="d-block text-dark font-weight-bolder">{{ __('Login Perbulan') }}</span>
                </h3>
            </div>
            <br>
            <form id="login-monthly-filter-chart" action="{{ route($routes . '.chartLoginMonthly') }}"
                class="form-inline" role="form">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="input-daterange input-group text-nowrap">
                            <div class="input-group-append" data-toggle="tooltip" title="Filter">
                                <span class="input-group-text">
                                    <i class="fa fa-filter"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control login__monthly_start"
                                name="login__monthly_start"
                                value="{{ request()->login__monthly_start ?? date('Y') }}" style="">
                            {{-- <div class="input-group-append">
                                <span class="input-group-text">/</span>
                            </div>
                            <input type="text" class="form-control login__monthly_end" name="login__monthly_end"
                                value="{{ request()->login__monthly_end ?? date('Y') }}" style=""> --}}
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <select name="login__monthly_perusahaan"
                            class="form-control base-plugin--select2-ajax login__monthly_perusahaan"
                           
                                    data-url="{{ rut('ajax.selectStruct', [
                                        'search' => 'root',
                                        'root_id' => auth()->user()->perusahaan_id,
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
                        <select name="followup_object_temuan"
                            class="form-control base-plugin--select2-ajax followup_object_temuan"
                            @if ($user->hasRole('Administrator') || $user->hasRole('Super Administrator')) disabled @else
                                data-url="{{ rut('ajax.selectStruct', [
                                    'search' => 'object_audit',
                                    'optstart_id' => 'all',
                                    'root_id' => auth()->user()->perusahaan_id,
                                ]) }}" data-url-origin="{{ rut('ajax.selectStruct', [
                                    'search' => 'object_audit',
                                ]) }}" @endif
                            data-placeholder="{{ __('Objek Audit') }}">
                            <option value="">{{ __('Objek Audit') }}</option>
                        </select>
                    </div> --}}
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="chart-wrapper">
                <div id="login-monthly-chart">
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
        .chart-login-monthly-wrapper .apexcharts-menu-item.exportSVG,
        .chart-login-monthly-wrapper .apexcharts-menu-item.exportCSV {
            display: none;
        }

        .chart-login-monthly-wrapper .apexcharts-title-text {
            white-space: normal;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(function() {
            iniFilterChartLoginMonthly();
            drawChartLoginMonthly();
        });

        var iniFilterChartLoginMonthly = function() {
            $('input.login__monthly_start').datepicker({
                    format: "yyyy",
                    viewMode: "years",
                    minViewMode: "years",
                    orientation: "bottom auto",
                    autoclose: true
                })
                .on('changeDate', function(value) {
                    drawChartLoginMonthly();
                    $('input.login__monthly_end').datepicker('destroy').datepicker({
                            format: "yyyy",
                            viewMode: "years",
                            minViewMode: "years",
                            orientation: "bottom auto",
                            startDate: new Date(value.date.valueOf()),
                            autoclose: true,
                        })
                        .on('changeDate', function(selected) {
                            drawChartLoginMonthly();
                        });
                    $('input.login__monthly_end').val('').focus();
                });

            $('input.login__monthly_end').datepicker({
                    format: "yyyy",
                    viewMode: "years",
                    minViewMode: "years",
                    orientation: "bottom auto",
                    startDate: new Date($('.login__monthly_start').val()),
                    autoclose: true,
                })
                .on('changeDate', function(selected) {
                    drawChartLoginMonthly();
                });

            $('.content-page')
                .on('change', 'select.login__monthly_perusahaan, select.followup_object_temuan', function() {
                    if ($(this).is('.login__monthly_perusahaan')) {
                        var me = $(this);
                        if (me.val()) {
                            var struct = $('select.followup_object_temuan');
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
                    drawChartLoginMonthly();
                });
            // $('select.login__monthly_perusahaan').trigger('change');
        }

        var drawChartLoginMonthly = function() {
            setTimeout(() => {
                var filter = $('#login-monthly-filter-chart');

                $.ajax({
                    url: filter.attr('action'),
                    method: 'POST',
                    data: {
                        _token: BaseUtil.getToken(),
                        login__monthly_start: filter.find('.login__monthly_start').val(),
                        login__monthly_end: filter.find('.login__monthly_end').val(),
                        login__monthly_perusahaan: filter.find('.login__monthly_perusahaan').val(),
                        followup_object_temuan: filter.find('.followup_object_temuan').val(),
                    },
                    success: function(resp) {
                        $('.chart-login-monthly-wrapper .chart-wrapper').find(
                                '#login-monthly-chart')
                            .remove();
                        $('.chart-login-monthly-wrapper .chart-wrapper').html(
                            `<div id="login-monthly-chart"></div>`);
                        renderChartLoginMonthly(resp);
                    },
                    error: function(resp) {
                        console.log(resp);
                    }
                });
            }, 10);
        }

        var renderChartLoginMonthly = function(options = {}) {
            var element = document.getElementById('login-monthly-chart');

            var defaultsOptions = {
                title: {
                    text: options.title.text ?? 'Tindak Lanjut Temuan',
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
                    width: [4, 0, 0, 0],
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
                    opacity: [0.3, 1, 1, 1],
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

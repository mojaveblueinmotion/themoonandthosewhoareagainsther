@extends('layouts.page')

@section('page')
    <div class="d-flex flex-row">
        @include($views . '.includes.profile-aside', ['tab' => 'notification'])

        <div class="flex-row-fluid ml-lg-8">
            <div class="card card-custom gutter-b">

                <div class="card-header py-3">
                    <div class="card-title align-items-start flex-column">
                        <h3 class="card-label font-weight-bolder text-dark">{{ __($title) }}</h3>
                        <span class="text-muted font-weight-bold font-size-sm mt-1">{{ __('Riwayat Notifikasi') }}</span>
                    </div>
                </div>

                <div class="card-body padding-20">
                    <table id="dataFilters" class="width-full">
                        <tbody>
                            <tr>
                                <td class="valign-top td-filter-reset width-80px pb-2">
                                    <div class="reset-filter hide mr-1">
                                        <button class="btn btn-secondary btn-icon width-full reset button"
                                            data-toggle="tooltip" data-original-title="Reset Filter"><i
                                                class="fas fa-sync"></i></button>
                                    </div>
                                    <div class="label-filter mr-1">
                                        <button class="btn btn-secondary btn-icon width-full button filter"
                                            data-toggle="tooltip" data-original-title="Filter"><i
                                                class="fas fa-filter"></i></button>
                                    </div>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-12 col-sm-6 col-xl-5 pb-2">
                                            <select id="moduleMain"
                                                class="form-control base-plugin--select2-ajax filter-control"
                                                data-post="module_name" data-placeholder="{{ __('Modul') }}">
                                                <option value="" selected>{{ __('Modul') }}</option>
                                                @foreach (\Base::getModulesMain() as $key => $name)
                                                    @if (in_array($key, ['dashboard', 'monitoring-temuan','monitoring', 'report', 'master', 'setting', 'profile', 'auth_login', 'auth_logout']))
                                                        @continue
                                                    @endif
                                                    <option value="{{ $key }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12 col-sm-6 col-xl-5 pb-2">
                                            <select disabled id="moduleSecondary"
                                                class="form-control base-plugin--select2-ajax filter-control"
                                                data-post="submodule_name" data-placeholder="{{ __('Submodul') }}">
                                                <option value="" selected>{{ __('Submodul') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </td>
                                <td class="td-btn-create text-right"></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="table-responsive">
                        @if (isset($tableStruct['datatable_1']))
                            <table id="datatable_1" class="table-bordered is-datatable table" style="width: 100%;"
                                data-url="{{ $tableStruct['url'] }}">
                                <thead>
                                    <tr>
                                        @foreach ($tableStruct['datatable_1'] as $struct)
                                            <th class="v-middle text-center" data-columns-name="{{ $struct['name'] ?? '' }}"
                                                data-columns-data="{{ $struct['data'] ?? '' }}"
                                                data-columns-label="{{ $struct['label'] ?? '' }}"
                                                data-columns-sortable="{{ $struct['sortable'] === true ? 'true' : 'false' }}"
                                                data-columns-width="{{ $struct['width'] ?? '' }}"
                                                data-columns-class-name="{{ $struct['className'] ?? '' }}"
                                                style="{{ isset($struct['width']) ? 'width: ' . $struct['width'] . '; ' : '' }}">
                                                {{ $struct['label'] }}
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        @endif
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        $('select#moduleMain').on('change', function() {
            var me = $(this);
            var selectedValue = me.val();
            $('#moduleSecondary').empty();

            $.ajax({
                url: '{{ url('setting/profile/getModulesSecondary') }}',
                type: 'GET',
                data: {
                    selectedValue: selectedValue
                },
                success: function(data) {
                    var plainSelect = $('#moduleSecondary');

                    plainSelect.empty();

                    $.each(data, function(key, name) {
                        plainSelect.append('<option value="">Pilih salah satu</option>');
                        plainSelect.append('<option value="' + key + '">' + name + '</option>');
                    });
                },
                error: function() {
                    console.log('Error fetching options.');
                }
            });
            $('select#moduleSecondary').prop('disabled', false);
        });
    </script>
@endpush

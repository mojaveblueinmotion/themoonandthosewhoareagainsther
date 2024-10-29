@extends('layouts.page')

@section('content-body')
    <div class="flex-column-fluid">
        <form action="{{ route($routes . '.submitSave', $record->id) }}" method="POST" autocomplete="off">
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            <div class="container-fluid">
                <div class="card card-custom">
                    <div class="card-header">
                        <h3 class="card-title">Residual Risk</h3>
                        <div class="card-toolbar">
                        @section('card-toolbar')
                            @include('layouts.forms.btnBackTop')
                        @show
                    </div>
                </div>
                <div class="card-body">
                    @include($views . '.includes.notes')
                    @include($views . '.includes.header')
                </div>
            </div>
        </div>
        <div class="container-fluid mt-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-custom">
                        <div class="card-header">
                            <h3 class="card-title">Detail Residual Risk</h3>
                            <div class="card-toolbar">
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label">{{ __('Prosentase') }}</label>
                                        <div class="col-md-8 parent-group">
                                                <div class="input-group">
                                                    <input name="prosentase" class="form-control angka--persen"
                                                    placeholder="{{ __('Prosentase') }}"
                                                    value="{{ $record->prosentase }}">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">%</div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label">{{ __('Level Kemungkinan') }}</label>
                                        <div class="col-md-8 parent-group">
                                            <select required name="level_kemungkinan_id"
                                                class="form-control base-plugin--select2-ajax"
                                                data-url="{{ rut('ajax.selectLevelKemungkinan', 'all') }}"
                                                placeholder="{{ __('Pilih Salah Satu') }}">
                                                <option value="">{{ __('Pilih Salah Satu') }}</option>
                                                @if (!empty($record->levelKemungkinan))
                                                    <option value="{{ $record->levelKemungkinan->id }}" selected>
                                                        {{ $record->levelKemungkinan->name }}</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label">{{ __('Level Dampak') }}</label>
                                        <div class="col-md-8 parent-group">
                                            <select required name="level_dampak_id"
                                                class="form-control base-plugin--select2-ajax"
                                                data-url="{{ rut('ajax.selectLevelDampak', 'all') }}"
                                                placeholder="{{ __('Pilih Salah Satu') }}">
                                                <option value="">{{ __('Pilih Salah Satu') }}</option>
                                                @if (!empty($record->levelDampak))
                                                    <option value="{{ $record->levelDampak->id }}" selected>
                                                        {{ $record->levelDampak->name }}</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label">{{ __('Tingkat Resiko') }}</label>
                                        <div class="col-md-8 parent-group">
                                            <select required name="tingkat_resiko_id"
                                                class="form-control base-plugin--select2-ajax"
                                                data-url="{{ rut('ajax.selectRiskRating') }}"
                                                placeholder="{{ __('Pilih Salah Satu') }}">
                                                <option value="">{{ __('Pilih Salah Satu') }}</option>
                                                @if (!empty($record->tingkatResiko))
                                                    <option value="{{ $record->tingkatResiko->id }}" selected>
                                                        {{ $record->tingkatResiko->name }}</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid mt-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-custom">
                        <div class="card-header">
                            <h3 class="card-title">Detail Residual Risk</h3>
                            <div class="card-toolbar">
                            </div>
                        </div>
                        <div class="card-body">
                            @include($views . '.detail.detail-grid-laporan')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if (request()->route()->getName() !=
                $routes . '.detail.show')
            <div class="container-fluid mt-5">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-custom">
                            <div class="card-header">
                                <h3 class="card-title">Alur Persetujuan</h3>
                                <div class="card-toolbar">
                                </div>
                            </div>
                            <div class="card-body text-center">
                                @php
                                    $colors = [
                                        1 => 'primary',
                                        2 => 'info',
                                    ];
                                @endphp
                                @if ($menu = \App\Models\Globals\Menu::where('module', 'risk-assessment.current-risk')->first())
                                    @if ($menu->flows()->get()->groupBy('order')->count() == null)
                                        <span class="label label-light-info font-weight-bold label-inline"
                                            data-toggle="tooltip">Belum
                                            memiliki Alur Persetujuan.</span>
                                    @else
                                        @foreach ($orders = $menu->flows()->get()->groupBy('order') as $i => $flows)
                                            @foreach ($flows as $j => $flow)
                                                <span
                                                    class="label label-light-{{ $colors[$flow->type] }} font-weight-bold label-inline"
                                                    data-toggle="tooltip"
                                                    title="{{ $flow->show_type }}">{{ $flow->role->name }}</span>
                                                @if (!($i === $orders->keys()->last() && $j === $flows->keys()->last()))
                                                    <i class="fas fa-angle-double-right text-muted mx-2"></i>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-custom">
                            <div class="card-header">
                                <h3 class="card-title">Aksi</h3>
                                <div class="card-toolbar">
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-end">
                                    Sebelum submit, pastikan data sesuai & alur persetujuan terisi.
                                    @include('layouts.forms.btnDropdownSubmit')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
</div>
</div>
@endsection
@section('buttons')
@endsection

@push('scripts')
    <script>
        $(".angka--persen").inputmask('currency', {
            alias: "numeric",
            prefix: "",
            groupSeparator: ",",
            radixPoint: ".",
            digits: 2,
            digitsOptional: !1,
            allowMinus: !1,
        });
    </script>
@endpush

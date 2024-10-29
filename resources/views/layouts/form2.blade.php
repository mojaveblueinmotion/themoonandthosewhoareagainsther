@extends('layouts.app')

@section('content')
@section('content-header')
    @include('layouts.base.subheadernobuttons')
@show
@php
    $colors = [
        1 => 'primary',
        2 => 'info',
    ];
@endphp
<div class="{{ $container ?? 'container-fluid' }}">
    <form action="@yield('action', '#')" method="POST" autocomplete="@yield('autocomplete', 'off')">
        @method($_method ?? 'POST')
        <div class="card card-custom mb-4">
            @section('card-header')
                <div class="card-header">
                    <h3 class="card-title">@yield('card-title', $title)</h3>
                    <div class="card-toolbar">
                    @section('card-toolbar')
                        @include('layouts.forms.btnBackTop')
                    @show
                </div>
            </div>
        @show

        <div class="card-body">
            @csrf
            @yield('card-body')
        </div>

        @section('buttons')
            <div class="card-footer">
                @section('card-footer')
                    <div class="d-flex justify-content-between">
                        @include('layouts.forms.btnBack')
                        @include('layouts.forms.btnSubmitPage')
                    </div>
                @show
            </div>
        @show
    </div>
    <div class="card card-custom">
        <div class="row">
            <div class="col-md-6">
                <div class="card card-custom" style="height:100%;">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex flex-column mr-5">
                                    <div class="card-header" style="padding-left:0.5rem!important;">
                                        <h5>
                                            Alur Persetujuan
                                        </h5>
                                    </div>
                                    <br>
                                    @section('approvals')
                                        <div class="d-flex align-items-center justify-content-center"
                                            style="margin-top:10px;">
                                            @php
                                                $menu = \App\Models\Globals\Menu::where('module', $module)->first();
                                            @endphp
                                            @if ($menu?->flows()?->get()?->groupBy('order')?->count() == 0)
                                                <span class="label label-light-info font-weight-bold label-inline"
                                                    data-toggle="tooltip">Data tidak tersedia.</span>
                                            @else
                                                @foreach ($orders = $menu?->flows()?->get()?->groupBy('order') as $i => $flows)
                                                    @foreach ($flows as $j => $flow)
                                                        <span
                                                            class="label label-light-{{ $colors[$flow->type] }} font-weight-bold label-inline"
                                                            data-toggle="tooltip"
                                                            style="height:100%;">{{ $flow->role->name }}</span>
                                                        @if (!($i === $orders->keys()->last() && $j === $flows->keys()->last()))
                                                            <i class="fas fa-angle-double-right text-muted mx-2"></i>
                                                        @endif
                                                    @endforeach
                                                @endforeach
                                            @endif
                                        </div>
                                    @show
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6" style="background:#e9eaf3;">
                <div class="card card-custom" style="height:100%;">
                    <div class="card-body">
                        <div
                            class="d-flex align-items-center justify-content-between flex-lg-wrap flex-xl-nowrap p-4">
                            <div class="d-flex flex-column mr-5">
                                <a href="#" class="h4 text-dark text-hover-primary mb-5">
                                    Informasi
                                </a>
                                <p class="text-dark-50">
                                    Sebelum submit, pastikan data sesuai & alur persetujuan terisi.
                                </p>
                            </div>
                            <div class="ml-lg-0 ml-xxl-6 ml-6 flex-shrink-0">
                                @php
                                    $menu = \App\Models\Globals\Menu::where('module', $module)->first();
                                    $count = $menu?->flows()?->count();
                                    $submit = $count == 0 ? 'disabled' : 'enabled';
                                @endphp
                                <div style="display: none">
                                    @include('layouts.forms.btnBack')
                                </div>
                                @section('submit-dropdown-content')
                                    @include('layouts.forms.btnDropdownSubmit')
                                @show
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
</div>
@endsection

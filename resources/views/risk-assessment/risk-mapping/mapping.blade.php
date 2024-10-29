@extends('layouts.modal')

@section('action', route($routes . '.store'))
@php
    $options = [
        'format' => 'mm/yyyy',
    ];
@endphp

@section('modal-body')

    <style>
        /* .table-container {
            position: relative;
        } */
        #grey {
            background-color: lightgrey;
            color: black;
            height: 60px;
            width: 60px !important;
        }

        #primary {
            background-color: #0D6EFD;
            color: white;
        }

        #green {
            background-color: #00b050;
            color: black;
        }

        #red {
            background-color: #fd0a0ae9;
            color: black;
        }

        #yellow {
            background-color: #ebf213;
            color: black;
        }

        #riskMap td {
            width: 120px;
            height: 80px;
            text-align: center;
            font-weight: bold;
        }

        #riskMap th {
            text-align: center;
            font-weight: bold;
        }

        #riskMap td #grey {
            height: 80px;
            width: 60px !important;
        }

        /* table td {
            height: 80px;
            width: 100px;
        } */
        .arrow {
            position: absolute;
            top: 50px;
            /* Adjust as needed */
            left: 60px;
            /* Adjust as needed */
        }

        .iconPin {
            background: url('{{ url('assets/media/pin1.png') }}');
            height: 20px;
            width: 20px;
            display: block;
            z-index: 10;
            /* Other styles here */
        }
    </style>

    <div class="row">
        @php
            $likehood_inherent = round($record->inherentRisk->total_likehood, 0, PHP_ROUND_HALF_DOWN);
            $impact_inherent = round($record->inherentRisk->total_impact, 0, PHP_ROUND_HALF_DOWN);
            $inherent_total = $likehood_inherent * $impact_inherent;

            $likehood_current = round($record->currentRisk->total_likehood, 0, PHP_ROUND_HALF_DOWN);
            $impact_current = round($record->currentRisk->total_impact, 0, PHP_ROUND_HALF_DOWN);
            $current_total = $likehood_current * $impact_current;

            $result = '';

            if ($inherent_total < 5) {
                $color = '#00b050';
            } elseif ($inherent_total > 10) {
                $color = '#ff0000';
            } else {
                $color = '#F2AF13';
            }
            $result .=
                $record->jenisResiko->name .
                " <b><span style='color:{$color};'> (" .
                $inherent_total .
                ')</span></b><br>';

            $x1 = 0;
            $x2 = 0;
            $y1 = 0;
            $y2 = 0;

        @endphp
        <div class="col-md-9">
            <table class="table-bordered table-responsive table" id="riskMap" style="width: 100%; text-align:center;">
                <thead>
                </thead>
                <tbody>
                    <tr>
                        <td id="grey" rowspan="6" style="transform: rotate(180deg);writing-mode: vertical-rl;width:0;">
                            IMPACT</td>
                        <td id="grey">5
                        </td>
                        <td id="yellow">5
                            @if ($likehood_inherent == 1 && $impact_inherent == 5)
                                <span data-toggle="tooltip" title="Inherent Score ({{ $inherent_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin1.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x1 = 1;
                                    $y1 = 5;
                                @endphp
                            @endif
                            @if ($likehood_current == 1 && $impact_current == 5)
                                <span data-toggle="tooltip" title="Residual Score ({{ $current_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin2.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x2 = 1;
                                    $y2 = 5;
                                @endphp
                            @endif
                        </td>
                        <td id="red">10
                            @if ($likehood_inherent == 2 && $impact_inherent == 5)
                                <span data-toggle="tooltip" title="Inherent Score ({{ $inherent_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin1.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x1 = 2;
                                    $y1 = 5;
                                @endphp
                            @endif
                            @if ($likehood_current == 2 && $impact_current == 5)
                                <span data-toggle="tooltip" title="Residual Score ({{ $current_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin2.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x2 = 2;
                                    $y2 = 5;
                                @endphp
                            @endif
                        </td>
                        <td id="red">15
                            @if ($likehood_inherent == 3 && $impact_inherent == 5)
                                <span data-toggle="tooltip" title="Inherent Score ({{ $inherent_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin1.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x1 = 3;
                                    $y1 = 5;
                                @endphp
                            @endif
                            @if ($likehood_current == 3 && $impact_current == 5)
                                <span data-toggle="tooltip" title="Residual Score ({{ $current_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin2.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x2 = 3;
                                    $y2 = 5;
                                @endphp
                            @endif
                        </td>
                        <td id="red">20
                            @if ($likehood_inherent == 4 && $impact_inherent == 5)
                                <span data-toggle="tooltip" title="Inherent Score ({{ $inherent_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin1.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x1 = 4;
                                    $y1 = 5;
                                @endphp
                            @endif
                            @if ($likehood_current == 4 && $impact_current == 5)
                                <span data-toggle="tooltip" title="Residual Score ({{ $current_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin2.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x2 = 4;
                                    $y2 = 5;
                                @endphp
                            @endif
                        </td>
                        <td id="red">25
                            @if ($likehood_inherent == 5 && $impact_inherent == 5)
                                <span data-toggle="tooltip" title="Inherent Score ({{ $inherent_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin1.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x1 = 5;
                                    $y1 = 5;
                                @endphp
                            @endif
                            @if ($likehood_current == 5 && $impact_current == 5)
                                <span data-toggle="tooltip" title="Residual Score ({{ $current_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin2.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x2 = 5;
                                    $y2 = 5;
                                @endphp
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td id="grey">4
                        </td>
                        <td id="green">4
                            @if ($likehood_inherent == 1 && $impact_inherent == 4)
                                <span data-toggle="tooltip" title="Inherent Score ({{ $inherent_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin1.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x1 = 1;
                                    $y1 = 4;
                                @endphp
                            @endif
                            @if ($likehood_current == 1 && ($impact_current == 4))
                                <span data-toggle="tooltip" title="Residual Score ({{ $current_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin2.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x2 = 1;
                                    $y2 = 4;
                                @endphp
                            @endif
                        </td>
                        <td id="yellow">8
                            @if ($likehood_inherent == 2 && $impact_inherent == 4)
                                <span data-toggle="tooltip" title="Inherent Score ({{ $inherent_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin1.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x1 = 2;
                                    $y1 = 4;
                                @endphp
                            @endif
                            @if ($likehood_current == 2 && $impact_current == 4)
                                <span data-toggle="tooltip" title="Residual Score ({{ $current_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin2.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x2 = 2;
                                    $y2 = 4;
                                @endphp
                            @endif
                        </td>
                        <td id="red">12
                            @if ($likehood_inherent == 3 && $impact_inherent == 4)
                                <span data-toggle="tooltip" title="Inherent Score ({{ $inherent_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin1.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x1 = 3;
                                    $y1 = 4;
                                @endphp
                            @endif
                            @if ($likehood_current == 3 && $impact_current == 4)
                                <span data-toggle="tooltip" title="Residual Score ({{ $current_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin2.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x2 = 3;
                                    $y2 = 4;
                                @endphp
                            @endif
                        </td>
                        <td id="red">16
                            @if ($likehood_inherent == 4 && $impact_inherent == 4)
                                <span data-toggle="tooltip" title="Inherent Score ({{ $inherent_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin1.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x1 = 4;
                                    $y1 = 4;
                                @endphp
                            @endif
                            @if ($likehood_current == 4 && $impact_current == 4)
                                <span data-toggle="tooltip" title="Residual Score ({{ $current_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin2.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x2 = 4;
                                    $y2 = 4;
                                @endphp
                            @endif
                        </td>
                        <td id="red">20
                            @if ($likehood_inherent == 5 && $impact_inherent == 4)
                                <span data-toggle="tooltip" title="Inherent Score ({{ $inherent_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin1.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x1 = 5;
                                    $y1 = 4;
                                @endphp
                            @endif
                            @if ($likehood_current == 5 && $impact_current == 4)
                                <span data-toggle="tooltip" title="Residual Score ({{ $current_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin2.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x2 = 5;
                                    $y2 = 4;
                                @endphp
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td id="grey">3
                        </td>
                        <td id="green">3
                            @if ($likehood_inherent == 1 && $impact_inherent == 3)
                                <span data-toggle="tooltip" title="Inherent Score ({{ $inherent_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin1.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x1 = 1;
                                    $y1 = 3;
                                @endphp
                            @endif
                            @if ($likehood_current == 1 && $impact_current == 3)
                                <span data-toggle="tooltip" title="Residual Score ({{ $current_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin2.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x2 = 1;
                                    $y2 = 3;
                                @endphp
                            @endif
                        </td>
                        <td id="yellow">6
                            @if ($likehood_inherent == 2 && $impact_inherent == 3)
                                <span data-toggle="tooltip" title="Inherent Score ({{ $inherent_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin1.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x1 = 2;
                                    $y1 = 3;
                                @endphp
                            @endif
                            @if ($likehood_current == 2 && $impact_current == 3)
                                <span data-toggle="tooltip" title="Residual Score ({{ $current_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin2.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x2 = 2;
                                    $y2 = 3;
                                @endphp
                            @endif
                        </td>
                        <td id="yellow">9
                            @if ($likehood_inherent == 3 && $impact_inherent == 3)
                                <span data-toggle="tooltip" title="Inherent Score ({{ $inherent_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin1.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x1 = 3;
                                    $y1 = 3;
                                @endphp
                            @endif
                            @if ($likehood_current == 3 && $impact_current == 3)
                                <span data-toggle="tooltip" title="Residual Score ({{ $current_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin2.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x2 = 3;
                                    $y2 = 3;
                                @endphp
                            @endif
                        </td>
                        <td id="red">12
                            @if ($likehood_inherent== 4 && $impact_inherent == 3)
                                <span data-toggle="tooltip" title="Inherent Score ({{ $inherent_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin1.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x1 = 4;
                                    $y1 = 3;
                                @endphp
                            @endif
                            @if ($likehood_current == 4 && $impact_current == 3)
                                <span data-toggle="tooltip" title="Residual Score ({{ $current_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin2.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x2 = 4;
                                    $y2 = 3;
                                @endphp
                            @endif
                        </td>
                        <td id="red">15
                            @if ($likehood_inherent == 5 && $impact_inherent == 3)
                                <span data-toggle="tooltip" title="Inherent Score ({{ $inherent_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin1.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x1 = 5;
                                    $y1 = 3;
                                @endphp
                            @endif
                            @if ($likehood_current == 5 && $impact_current == 3)
                                <span data-toggle="tooltip" title="Residual Score ({{ $current_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin2.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x2 = 5;
                                    $y2 = 3;
                                @endphp
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td id="grey">2
                        </td>
                        <td id="green">2
                            @if ($likehood_inherent == 1 && $impact_inherent == 2)
                                <span data-toggle="tooltip" title="Inherent Score ({{ $inherent_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin1.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x1 = 1;
                                    $y1 = 2;
                                @endphp
                            @endif
                            @if ($likehood_current == 1 && $impact_current == 2)
                                <span data-toggle="tooltip" title="Residual Score ({{ $current_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin2.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x2 = 1;
                                    $y2 = 2;
                                @endphp
                            @endif
                        </td>
                        <td id="green">4
                            @if ($likehood_inherent == 2 && $impact_inherent == 2)
                                <span data-toggle="tooltip" title="Inherent Score ({{ $inherent_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin1.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x1 = 2;
                                    $y1 = 2;
                                @endphp
                            @endif
                            @if ($likehood_current == 2 && $impact_current == 2)
                                <span data-toggle="tooltip" title="Residual Score ({{ $current_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin2.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x2 = 2;
                                    $y2 = 2;
                                @endphp
                            @endif
                        </td>
                        <td id="yellow">6
                            @if ($likehood_inherent == 3 && $impact_inherent == 2)
                                <span data-toggle="tooltip" title="Inherent Score ({{ $inherent_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin1.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x1 = 3;
                                    $y1 = 2;
                                @endphp
                            @endif
                            @if ($likehood_current == 3 && $impact_current == 2)
                                <span data-toggle="tooltip" title="Residual Score ({{ $current_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin2.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x2 = 3;
                                    $y2 = 2;
                                @endphp
                            @endif
                        </td>
                        <td id="yellow">8
                            @if ($likehood_inherent == 4 && $impact_inherent == 2)
                                <span data-toggle="tooltip" title="Inherent Score ({{ $inherent_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin1.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x1 = 4;
                                    $y1 = 2;
                                @endphp
                            @endif
                            @if ($likehood_current == 4 && $impact_current == 2)
                                <span data-toggle="tooltip" title="Residual Score ({{ $current_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin2.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x2 = 4;
                                    $y2 = 2;
                                @endphp
                            @endif
                        </td>
                        <td id="red">10
                            @if ($likehood_inherent == 5 && $impact_inherent == 2)
                                <span data-toggle="tooltip" title="Inherent Score ({{ $inherent_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin1.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x1 = 5;
                                    $y1 = 2;
                                @endphp
                            @endif
                            @if ($likehood_current == 5 && $impact_current == 2)
                                <span data-toggle="tooltip" title="Residual Score ({{ $current_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin2.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x2 = 5;
                                    $y2 = 2;
                                @endphp
                            @endif
                        </td>
                    </tr>
                    <tr id="grey">
                        <td id="grey">1
                        </td>
                        <td id="green">1
                            @if ($likehood_inherent == 1 && $impact_inherent == 1)
                                <span data-toggle="tooltip" title="Inherent Score ({{ $inherent_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin1.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x1 = 1;
                                    $y1 = 1;
                                @endphp
                            @endif
                            @if ($likehood_current == 1 && $impact_current == 1)
                                <span data-toggle="tooltip" title="Residual Score ({{ $current_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin2.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x2 = 1;
                                    $y2 = 1;
                                @endphp
                            @endif
                        </td>
                        <td id="green">2
                            @if ($likehood_inherent == 2 && $likehood_inherent == 1)
                                <span data-toggle="tooltip" title="Inherent Score ({{ $inherent_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin1.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x1 = 2;
                                    $y1 = 1;
                                @endphp
                            @endif
                            @if ($likehood_current == 2 && $impact_current == 1)
                                <span data-toggle="tooltip" title="Residual Score ({{ $current_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin2.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x2 = 2;
                                    $y2 = 1;
                                @endphp
                            @endif
                        </td>
                        <td id="green">3
                            @if ($likehood_inherent == 3 && $impact_inherent == 1)
                                <span data-toggle="tooltip" title="Inherent Score ({{ $inherent_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin1.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x1 = 3;
                                    $y1 = 1;
                                @endphp
                            @endif
                            @if ($likehood_current == 3 && $impact_current == 1)
                                <span data-toggle="tooltip" title="Residual Score ({{ $current_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin2.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x2 = 3;
                                    $y2 = 1;
                                @endphp
                            @endif
                        </td>
                        <td id="green">4
                            @if ($likehood_inherent == 4 && $impact_inherent == 1)
                                <span data-toggle="tooltip" title="Inherent Score ({{ $inherent_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin1.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x1 = 4;
                                    $y1 = 1;
                                @endphp
                            @endif
                            @if ($likehood_current == 4 && $impact_current == 1)
                                <span data-toggle="tooltip" title="Residual Score ({{ $current_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin2.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x2 = 4;
                                    $y2 = 1;
                                @endphp
                            @endif
                        </td>
                        <td id="yellow">5
                            @if ($likehood_inherent == 5 && $impact_inherent == 1)
                                <span data-toggle="tooltip" title="Inherent Score ({{ $inherent_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin1.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x1 = 5;
                                    $y1 = 1;
                                @endphp
                            @endif
                            @if ($likehood_current == 5 && $impact_current == 1)
                                <span data-toggle="tooltip" title="Residual Score ({{ $current_total }})"
                                    data-placement="top">
                                    <img src="{{ asset('assets/media/pin2.png') }}" width="40" height="40"
                                        alt="">
                                </span>
                                @php
                                    $x2 = 5;
                                    $y2 = 1;
                                @endphp
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td id="grey"></td>
                        <td id="grey">1</td>
                        <td id="grey">2</td>
                        <td id="grey">3</td>
                        <td id="grey">4</td>
                        <td id="grey">5</td>
                    </tr>

                    <tr>
                        <th>

                        </th>
                        <th id="grey" colspan="6">
                            LIKELIHOOD
                        </th>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-2">
            <table class="table-collapse table" style="width: 100%; text-align:center;" id="riskMap">
                <tr>
                    <td id="green"></td>
                    {{-- <td colspan="2">Low Risk {{ $x1 . $x2 . $y1 . $y2}}</td> --}}
                    <td colspan="2">Low Risk</td>
                </tr>
                <tr>
                    <td id="yellow"></td>
                    <td colspan="2">Medium Risk</td>
                </tr>
                <tr>
                    <td id="red"></td>
                    <td colspan="2">High Risk</td>
                </tr>
                <tr>
                    <td id="grey">Inherent Risk</td>
                    <td colspan="2">
                        <div class="d-flex justify-content-between">
                            <div>{!! $record->getInherentLikelihoodScore() !!}</div>
                            <div>{!! $record->getInherentImpactScore() !!}</div>
                            <div>{!! $record->getTotalInherentScore() !!}</div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td id="primary">Residual Risk</td>
                    <td colspan="2">
                        <div class="d-flex justify-content-between">
                            <div>{!! $record->getCurrentLikelihoodScore() !!}</div>
                            <div>{!! $record->getCurrentImpactScore() !!}</div>
                            <div>{!! $record->getTotalCurrentScore() !!}</div>
                        </div>
                    </td>
                </tr>
            </table>

        </div>
    </div>

    {{-- {!! $result !!} --}}
@endsection

@section('buttons')
@endsection

@push('scripts')
    <script>
        $(function() {
            $('.modal-dialog-right-bottom').removeClass('modal-md').addClass('modal-xl');
        });
    </script>
@endpush

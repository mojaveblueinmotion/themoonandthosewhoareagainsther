@extends('layouts.modal')

@section('action', route($routes.'.store'))
@php
$options = [
"format" => "mm/yyyy",
];
@endphp

@section('modal-body')

<style>
	#grey {
		background-color:lightgrey;
		color:black;
	}



	#green {
		background-color:#00b050;
		color:black;
	}

	#red {
		background-color:#fd0a0ae9;
		color:black;
	}

	#yellow {
		background-color:#ebf213;
		color:black;
	}
	#riskMap td{
		width:80px;
		text-align:center;
		font-weight:bold;
	}
	#riskMap th{
		text-align:center;
		font-weight:bold;
	}

    table td {
        height: 60px;
        width: 60px;
    }
</style>
<div class="row">
    @php

        $totalInherent = $record->inherentRisk->total_impact * $record->inherentRisk->total_likehood;
        $totalCurrent = $record->currentRisk->total_impact * $record->currentRisk->total_likehood;
        $result = "";

        if($totalInherent < 5){
            $color = '#00b050';
        }elseif($totalInherent > 10){
            $color = '#ff0000';
        }else{
            $color = '#F2AF13';
        }
        $result .= $record->jenisResiko->name . " <b><span style='color:{$color};'> (" .$totalInherent . ")</span></b><br>";

    @endphp
    <div class="col-md-8">
        <table class="table table-bordered table-responsive" id="riskMap" style="width: 100%; text-align:center;">
            <thead>
                <tr>
                    <th>

                    </th>
                    <th id="grey" colspan="5">
                        IMPACT
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td id="grey">Likelihood</td>
                    <td id="grey">1</td>
                    <td id="grey">2</td>
                    <td id="grey">3</td>
                    <td id="grey">4</td>
                    <td id="grey">5</td>
                </tr>
                <tr id="grey">
                    <td id="grey">1
                     </td>
                    <td id="green">1 
                        @if($totalInherent <= 1)
                            <span data-toggle="tooltip" title="Inherent Score ({{ $totalInherent }})" data-placement="top">
                                <i class="fas fa-thumbtack"></i>
                            </span>
                        @endif
                        @if($totalCurrent <= 1)
                            <span data-toggle="tooltip" title="Residual Score ({{ $totalCurrent }})" data-placement="top">
                                <i class="fas fa-thumbtack"></i>
                            </span>
                        @endif
                     </td>
                    <td id="green">2 
                        @if($totalInherent <= 2 && $totalInherent > 1)
                            <span data-toggle="tooltip" title="Inherent Score ({{ $totalInherent }})" data-placement="top">
                                <i class="fas fa-thumbtack"></i>
                            </span>
                        @endif
                        @if($totalCurrent <= 2 && $totalCurrent > 1)
                            <span data-toggle="tooltip" title="Residual Score ({{ $totalCurrent }})" data-placement="top">
                                <i class="fas fa-thumbtack text-primary"></i>
                            </span>
                        @endif
                     </td>
                    <td id="green">3 
                        @if($totalInherent <= 3 && $totalInherent > 2)
                            <span data-toggle="tooltip" title="Inherent Score ({{ $totalInherent }})" data-placement="top">
                                <i class="fas fa-thumbtack"></i>
                            </span>
                        @endif
                        @if($totalCurrent <= 3 && $totalCurrent > 2)
                            <span data-toggle="tooltip" title="Residual Score ({{ $totalCurrent }})" data-placement="top">
                                <i class="fas fa-thumbtack text-primary"></i>
                            </span>
                        @endif
                     </td>
                    <td id="green">4 
                        @if($totalInherent <= 4 && $totalInherent > 3)
                            <span data-toggle="tooltip" title="Inherent Score ({{ $totalInherent }})" data-placement="top">
                                <i class="fas fa-thumbtack"></i>
                            </span>
                        @endif
                        @if($totalCurrent <= 4 && $totalCurrent > 3)
                            <span data-toggle="tooltip" title="Residual Score ({{ $totalCurrent }})" data-placement="top">
                                <i class="fas fa-thumbtack text-primary"></i>
                            </span>
                        @endif
                     </td>
                    <td id="yellow">5 
                        @if($totalInherent <= 5 && $totalInherent > 4)
                            <span data-toggle="tooltip" title="Inherent Score ({{ $totalInherent }})" data-placement="top">
                                <i class="fas fa-thumbtack"></i>
                            </span>
                        @endif
                        @if($totalCurrent <= 5 && $totalCurrent > 4)
                            <span data-toggle="tooltip" title="Residual Score ({{ $totalCurrent }})" data-placement="top">
                                <i class="fas fa-thumbtack text-primary"></i>
                            </span>
                        @endif
                     </td>
                </tr>
                <tr>
                    <td id="grey">2
                     </td>
                    <td id="green">2 
                        @if($totalInherent <= 2 && $totalInherent > 1)
                            <span data-toggle="tooltip" title="Inherent Score ({{ $totalInherent }})" data-placement="top">
                                <i class="fas fa-thumbtack"></i>
                            </span>
                        @endif
                        @if($totalCurrent <= 2 && $totalCurrent > 1)
                            <span data-toggle="tooltip" title="Residual Score ({{ $totalCurrent }})" data-placement="top">
                                <i class="fas fa-thumbtack text-primary"></i>
                            </span>
                        @endif
                     </td>
                    <td id="green">4 
                        @if($totalInherent <= 4 && $totalInherent > 3)
                            <span data-toggle="tooltip" title="Inherent Score ({{ $totalInherent }})" data-placement="top">
                                <i class="fas fa-thumbtack"></i>
                            </span>
                        @endif
                        @if($totalCurrent <= 4 && $totalCurrent > 3)
                            <span data-toggle="tooltip" title="Residual Score ({{ $totalCurrent }})" data-placement="top">
                                <i class="fas fa-thumbtack text-primary"></i>
                            </span>
                        @endif
                     </td>
                    <td id="yellow">6 
                        @if($totalInherent <= 6 && $totalInherent > 5)
                            <span data-toggle="tooltip" title="Inherent Score ({{ $totalInherent }})" data-placement="top">
                                <i class="fas fa-thumbtack"></i>
                            </span>
                        @endif
                        @if($totalCurrent <= 6 && $totalCurrent > 5)
                            <span data-toggle="tooltip" title="Residual Score ({{ $totalCurrent }})" data-placement="top">
                                <i class="fas fa-thumbtack text-primary"></i>
                            </span>
                        @endif
                     </td>
                    <td id="yellow">8 
                        @if($totalInherent <= 8 && $totalInherent > 6)
                            <span data-toggle="tooltip" title="Inherent Score ({{ $totalInherent }})" data-placement="top">
                                <i class="fas fa-thumbtack"></i>
                            </span>
                        @endif
                        @if($totalCurrent <= 8 && $totalCurrent > 6)
                            <span data-toggle="tooltip" title="Residual Score ({{ $totalCurrent }})" data-placement="top">
                                <i class="fas fa-thumbtack text-primary"></i>
                            </span>
                        @endif
                     </td>
                    <td id="red">10 
                        @if($totalInherent <= 10 && $totalInherent > 9)
                            <span data-toggle="tooltip" title="Inherent Score ({{ $totalInherent }})" data-placement="top">
                                <i class="fas fa-thumbtack"></i>
                            </span>
                        @endif
                        @if($totalCurrent <= 10 && $totalCurrent > 9)
                            <span data-toggle="tooltip" title="Residual Score ({{ $totalCurrent }})" data-placement="top">
                                <i class="fas fa-thumbtack text-primary"></i>
                            </span>
                        @endif
                     </td>
                </tr>
                <tr>
                    <td id="grey">3
                     </td>
                    <td id="green">3 
                        @if($totalInherent <= 3 && $totalInherent > 2)
                            <span data-toggle="tooltip" title="Inherent Score ({{ $totalInherent }})" data-placement="top">
                                <i class="fas fa-thumbtack"></i>
                            </span>
                        @endif
                        @if($totalCurrent <= 3 && $totalCurrent > 2)
                            <span data-toggle="tooltip" title="Residual Score ({{ $totalCurrent }})" data-placement="top">
                                <i class="fas fa-thumbtack text-primary"></i>
                            </span>
                        @endif
                     </td>
                    <td id="yellow">6 
                        @if($totalInherent <= 6 && $totalInherent > 5)
                            <span data-toggle="tooltip" title="Inherent Score ({{ $totalInherent }})" data-placement="top">
                                <i class="fas fa-thumbtack"></i>
                            </span>
                        @endif
                        @if($totalCurrent <= 6 && $totalCurrent > 5)
                            <span data-toggle="tooltip" title="Residual Score ({{ $totalCurrent }})" data-placement="top">
                                <i class="fas fa-thumbtack text-primary"></i>
                            </span>
                        @endif
                     </td>
                    <td id="yellow">9 
                        @if($totalInherent <= 9 && $totalInherent > 8)
                            <span data-toggle="tooltip" title="Inherent Score ({{ $totalInherent }})" data-placement="top">
                                <i class="fas fa-thumbtack"></i>
                            </span>
                        @endif
                        @if($totalCurrent <= 9 && $totalCurrent > 8)
                            <span data-toggle="tooltip" title="Residual Score ({{ $totalCurrent }})" data-placement="top">
                                <i class="fas fa-thumbtack text-primary"></i>
                            </span>
                        @endif
                     </td>
                    <td id="red">12 
                        @if($totalInherent <= 12 && $totalInherent > 10)
                            <span data-toggle="tooltip" title="Inherent Score ({{ $totalInherent }})" data-placement="top">
                                <i class="fas fa-thumbtack"></i>
                            </span>
                        @endif
                        @if($totalCurrent <= 12 && $totalCurrent > 10)
                            <span data-toggle="tooltip" title="Residual Score ({{ $totalCurrent }})" data-placement="top">
                                <i class="fas fa-thumbtack text-primary"></i>
                            </span>
                        @endif
                     </td>
                    <td id="red">15 
                        @if($totalInherent <= 15 && $totalInherent > 12)
                            <span data-toggle="tooltip" title="Inherent Score ({{ $totalInherent }})" data-placement="top">
                                <i class="fas fa-thumbtack"></i>
                            </span>
                        @endif
                        @if($totalCurrent <= 15 && $totalCurrent > 12)
                            <span data-toggle="tooltip" title="Residual Score ({{ $totalCurrent }})" data-placement="top">
                                <i class="fas fa-thumbtack text-primary"></i>
                            </span>
                        @endif
                     </td>
                </tr>
                <tr>
                    <td id="grey">4
                     </td>
                    <td id="green">4 
                        @if($totalInherent <= 4 && $totalInherent > 3)
                            <span data-toggle="tooltip" title="Inherent Score ({{ $totalInherent }})" data-placement="top">
                                <i class="fas fa-thumbtack"></i>
                            </span>
                        @endif
                        @if($totalCurrent <= 4 && $totalCurrent > 3)
                            <span data-toggle="tooltip" title="Residual Score ({{ $totalCurrent }})" data-placement="top">
                                <i class="fas fa-thumbtack text-primary"></i>
                            </span>
                        @endif
                     </td>
                    <td id="yellow">8 
                        @if($totalInherent <= 8 && $totalInherent > 6)
                            <span data-toggle="tooltip" title="Inherent Score ({{ $totalInherent }})" data-placement="top">
                                <i class="fas fa-thumbtack"></i>
                            </span>
                        @endif
                        @if($totalCurrent <= 8 && $totalCurrent > 6)
                            <span data-toggle="tooltip" title="Residual Score ({{ $totalCurrent }})" data-placement="top">
                                <i class="fas fa-thumbtack text-primary"></i>
                            </span>
                        @endif
                     </td>
                    <td id="red">12 
                        @if($totalInherent <= 12 && $totalInherent > 10)
                            <span data-toggle="tooltip" title="Inherent Score ({{ $totalInherent }})" data-placement="top">
                                <i class="fas fa-thumbtack"></i>
                            </span>
                        @endif
                        @if($totalCurrent <= 12 && $totalCurrent > 10)
                            <span data-toggle="tooltip" title="Residual Score ({{ $totalCurrent }})" data-placement="top">
                                <i class="fas fa-thumbtack text-primary"></i>
                            </span>
                        @endif
                     </td>
                    <td id="red">16 
                        @if($totalInherent <= 16 && $totalInherent > 15)
                            <span data-toggle="tooltip" title="Inherent Score ({{ $totalInherent }})" data-placement="top">
                                <i class="fas fa-thumbtack"></i>
                            </span>
                        @endif
                        @if($totalCurrent <= 16 && $totalCurrent > 15)
                            <span data-toggle="tooltip" title="Residual Score ({{ $totalCurrent }})" data-placement="top">
                                <i class="fas fa-thumbtack text-primary"></i>
                            </span>
                        @endif
                     </td>
                    <td id="red">20 
                        @if($totalInherent <= 20 && $totalInherent > 16)
                            <span data-toggle="tooltip" title="Inherent Score ({{ $totalInherent }})" data-placement="top">
                                <i class="fas fa-thumbtack"></i>
                            </span>
                        @endif
                        @if($totalCurrent <= 20 && $totalCurrent > 16)
                            <span data-toggle="tooltip" title="Residual Score ({{ $totalCurrent }})" data-placement="top">
                                <i class="fas fa-thumbtack text-primary"></i>
                            </span>
                        @endif
                     </td>
                </tr>
                <tr>
                    <td id="grey">5 
                     </td>
                    <td id="yellow">5 
                        @if($totalInherent <= 5 && $totalInherent > 4)
                            <span data-toggle="tooltip" title="Inherent Score ({{ $totalInherent }})" data-placement="top">
                                <i class="fas fa-thumbtack"></i>
                            </span>
                        @endif
                        @if($totalCurrent <= 5 && $totalCurrent > 4)
                            <span data-toggle="tooltip" title="Residual Score ({{ $totalCurrent }})" data-placement="top">
                                <i class="fas fa-thumbtack text-primary"></i>
                            </span>
                        @endif
                     </td>
                    <td id="red">10 
                        @if($totalInherent <= 10 && $totalInherent > 9)
                            <span data-toggle="tooltip" title="Inherent Score ({{ $totalInherent }})" data-placement="top">
                                <i class="fas fa-thumbtack"></i>
                            </span>
                        @endif
                        @if($totalCurrent <= 10 && $totalCurrent > 9)
                            <span data-toggle="tooltip" title="Residual Score ({{ $totalCurrent }})" data-placement="top">
                                <i class="fas fa-thumbtack text-primary"></i>
                            </span>
                        @endif
                     </td>
                    <td id="red">15 
                        @if($totalInherent <= 15 && $totalInherent > 12)
                            <span data-toggle="tooltip" title="Inherent Score ({{ $totalInherent }})" data-placement="top">
                                <i class="fas fa-thumbtack"></i>
                            </span>
                        @endif
                        @if($totalCurrent <= 15 && $totalCurrent > 12)
                            <span data-toggle="tooltip" title="Residual Score ({{ $totalCurrent }})" data-placement="top">
                                <i class="fas fa-thumbtack text-primary"></i>
                            </span>
                        @endif
                     </td>
                    <td id="red">20 
                        @if($totalInherent <= 20 && $totalInherent > 16)
                            <span data-toggle="tooltip" title="Inherent Score ({{ $totalInherent }})" data-placement="top">
                                <i class="fas fa-thumbtack"></i>
                            </span>
                        @endif
                        @if($totalCurrent <= 20 && $totalCurrent > 16)
                            <span data-toggle="tooltip" title="Residual Score ({{ $totalCurrent }})" data-placement="top">
                                <i class="fas fa-thumbtack text-primary"></i>
                            </span>
                        @endif
                     </td>
                    <td id="red">25 
                        @if($totalInherent <= 25 && $totalInherent > 20)
                            <span data-toggle="tooltip" title="Inherent Score ({{ $totalInherent }})" data-placement="top">
                                <i class="fas fa-thumbtack"></i>
                            </span>
                        @endif
                        @if($totalCurrent <= 25 && $totalCurrent > 20)
                            <span data-toggle="tooltip" title="Residual Score ({{ $totalCurrent }})" data-placement="top">
                                <i class="fas fa-thumbtack text-primary"></i>
                            </span>
                        @endif
                     </td>
                </tr>
            </tbody>
        </table>
        
    </div>
    <div class="col-md-4">
        <table class="table table-collapse" style="width: 100%; text-align:center;" id="riskMap">
            <tr>
              <td id="green"></td>
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

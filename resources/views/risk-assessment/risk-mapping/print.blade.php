<html>

<head>
    <title>{{ $title }}</title>
    @include('layouts.partials.print-styles')
</head>

<body class="page">
    <header>
        <table style="border:none; width: 100%;">
            <tr>
                <td style="border:none;" width="150px">
                    <img src="{{ getLogo() }}" style="max-width: 150px; max-height: 60px">
                </td>
                <td style="border:none;  text-align: center; font-size: 14pt;" width="auto">
                    <b>{{ __('Residual Risk') }}</b>
                </td>
                <td style="border:none; text-align: right;" width="150px">
                    <b></b>
                </td>
            </tr>
        </table>
        <hr>
        <br>
    </header>
    <footer>
        <table width="100%" border="0" style="border: none;">
            <tr>
                <td style="width: 10%;border: none;" align="right"><span class="pagenum"></span></td>
            </tr>
        </table>
    </footer>
    <main>
        <table style="border:none; width:100%;">
            <tr>
                <td style="border:none; width:48%;">
                    <table style="border:none; width:100%;">
                        <tr>
                            <td style="border: none; width: 80px;">{{ __('Tipe Audit') }}</td>
                            <td style="border: none; width: 10px; text-align: left;">:</td>
                            <td style="border: none; text-align: left;">
                                {{ $record->riskRegister->type->name }}
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none; width: 80px;">{{ __('Periode') }}</td>
                            <td style="border: none; width: 10px; text-align: left;">:</td>
                            <td style="border: none; text-align: left;">
                                {{ $record->riskRegister->periode->translatedFormat('Y') }}
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none; width: 80px;">{{ __('Subject Audit') }}</td>
                            <td style="border: none; width: 10px; text-align: left;">:</td>
                            <td style="border: none; text-align: left;">
                                {{ $record->riskRegister->subject->name }}</td>
                        </tr>
                        {{-- <tr>
                            <td style="border: none; width: 80px; vertical-align: top">{{ __('Sasaran') }}</td>
                            <td style="border: none; width: 10px; text-align: left;vertical-align: top">:</td>
                            <td style="border: none; text-align: justify; vertical-align: top">
                                {{ $record->riskRegister->sasaran }}
                            </td>
                        </tr> --}}
                    </table>
                </td>
            </tr>
        </table>
        <br>
        <div style="text-align:left;font-weight: bold;">Detail Risk Register :</div>
        <ol style="margin-left: 1.5em;padding-left:0px;">
            @foreach ($record->riskRegister->details as $detail)
                <li style="margin: 0 !important;padding: 0 !important;">
                    <ol style="list-style-type: lower-alpha">
                        <li style="margin-left: -1em;">
                            <table style="border: none; width: 100%">
                                <tr>
                                    <td style="border: none; font-weight: bold; width: 160px;">{{ __('Main Process') }}
                                    </td>
                                    <td style="border: none; width: 10px; text-align: left;">:</td>
                                    <td style="border: none; text-align: left;">
                                        {{ $detail->mainProcess->name }}
                                    </td>
                                </tr>
                            </table>
                        </li>
                        <li style="margin-left: -1em;">
                            <table style="border: none; width: 100%">
                                <tr>
                                    <td style="border: none; font-weight: bold; width: 160px;">{{ __('Sub Process') }}
                                    </td>
                                    <td style="border: none; width: 10px; text-align: left;">:</td>
                                    <td style="border: none; text-align: left;">
                                        {{ $detail->subProcess->name }}
                                    </td>
                                </tr>
                            </table>
                        </li>
                        <li style="margin-left: -1em;">
                            <table style="border: none; width: 100%">
                                <tr>
                                    <td style="border: none; font-weight: bold; vertical-align:top; width: 160px;">
                                        {{ __('Risk Event') }}</td>
                                    <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                                    <td style="border: none; vertical-align:top; text-align: justify;">
                                        {{ $detail->peristiwa }}
                                    </td>
                                </tr>
                            </table>
                        </li>
                        <li style="margin-left: -1em;">
                            <table style="border: none; width: 100%">
                                <tr>
                                    <td style="border: none; font-weight: bold; vertical-align:top; width: 160px;">
                                        {{ __('Risk Cause') }}</td>
                                    <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                                    <td style="border: none; vertical-align:top; text-align: justify;">
                                        {{ $detail->penyebab }}
                                    </td>
                                </tr>
                            </table>
                        </li>
                        <li style="margin-left: -1em;">
                            <table style="border: none; width: 100%">
                                <tr>
                                    <td style="border: none; font-weight: bold; vertical-align:top; width: 160px;">
                                        {{ __('Risk Impact') }}</td>
                                    <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                                    <td style="border: none; vertical-align:top; text-align: justify;">
                                        {{ $detail->dampak }}
                                    </td>
                                </tr>
                            </table>
                        </li>
                    </ol>
                    <br>

                    <div style="text-align:left;font-weight: bold;">Inherent Risk :</div>
                    <div style="text-align:left;font-weight: bold;">Inherent Risk Likelihood :</div>
                    <ol style="padding-left:20px;" style="list-style-type: lower-alpha">
                        {{-- Likelihood --}}
                        <li>
                            <table style="border: none; width: 100%">
                                <tr>
                                    <td style="border: none; font-weight: bold; vertical-align:top; width: 150px;">
                                        {{ __('Complexity (30%)') }}</td>
                                    <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                                    <td style="border: none; vertical-align:top; text-align: left;">
                                        {{ $detail->inherentRisk->complexity }}</td>

                                    <td style="border: none; font-weight: bold; vertical-align:top; width: 150px;">
                                        {{ __('Volume (35%)') }}</td>
                                    <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                                    <td style="border: none; vertical-align:top; text-align: left;">
                                        {{ $detail->inherentRisk->volume }}
                                    </td>
                                </tr>
                            </table>
                        </li>
                        <li>
                            <table style="border: none; width: 100%">
                                <tr>
                                    <td style="border: none; font-weight: bold; vertical-align:top; width: 150px;">
                                        {{ __('Known Issue (20%)') }}</td>
                                    <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                                    <td style="border: none; vertical-align:top; text-align: left;">
                                        {{ $detail->inherentRisk->known_issue }}
                                    </td>

                                    <td style="border: none; font-weight: bold; vertical-align:top; width: 150px;">
                                        {{ __('Changing Process & People (15%)') }}</td>
                                    <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                                    <td style="border: none; vertical-align:top; text-align: left;">
                                        {{ $detail->inherentRisk->chaning_process }}
                                    </td>
                                </tr>
                                </tr>
                            </table>
                        </li>
                    </ol>
                    <div style="text-align:left;font-weight: bold;">Inherent Risk Impact :</div>
                    <ol style="padding-left:20px;" style="list-style-type: lower-alpha">
                        {{-- Impact --}}
                        <li>
                            <table style="border: none; width: 100%">
                                <tr>
                                    <td style="border: none; font-weight: bold; vertical-align:top; width: 150px;">
                                        {{ __('Materiality (40%)') }}</td>
                                    <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                                    <td style="border: none; vertical-align:top; text-align: left;">
                                        {{ $detail->inherentRisk->materiality }}</td>

                                    <td style="border: none; font-weight: bold; vertical-align:top; width: 150px;">
                                        {{ __('Operational (30%)') }}</td>
                                    <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                                    <td style="border: none; vertical-align:top; text-align: left;">
                                        {{ $detail->inherentRisk->operational }}
                                    </td>
                                </tr>
                            </table>
                        </li>
                        <li>
                            <table style="border: none; width: 100%">
                                <tr>
                                    <td style="border: none; font-weight: bold; vertical-align:top; width: 150px;">
                                        {{ __('Legal & Compliance (30%)') }}</td>
                                    <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                                    <td style="border: none; vertical-align:top; text-align: left;">
                                        {{ $detail->inherentRisk->legal }}
                                    </td>
                                </tr>
                            </table>
                        </li>
                    </ol>

                    <br>

                    <div style="text-align:left;font-weight: bold;">Residual Risk :</div>
                    <div style="text-align:left;font-weight: bold;">Residual Risk Likelihood :</div>
                    <ol style="padding-left:20px;" style="list-style-type: lower-alpha">
                        {{-- Likelihood --}}
                        <li>
                            <table style="border: none; width: 100%">
                                <tr>
                                    <td style="border: none; font-weight: bold; vertical-align:top; width: 150px;">
                                        {{ __('Complexity (30%)') }}</td>
                                    <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                                    <td style="border: none; vertical-align:top; text-align: left;">
                                        {{ $detail->currentRisk->complexity }}</td>

                                    <td style="border: none; font-weight: bold; vertical-align:top; width: 150px;">
                                        {{ __('Volume (35%)') }}</td>
                                    <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                                    <td style="border: none; vertical-align:top; text-align: left;">
                                        {{ $detail->currentRisk->volume }}
                                    </td>
                                </tr>
                            </table>
                        </li>
                        <li>
                            <table style="border: none; width: 100%">
                                <tr>
                                    <td style="border: none; font-weight: bold; vertical-align:top; width: 150px;">
                                        {{ __('Known Issue (20%)') }}</td>
                                    <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                                    <td style="border: none; vertical-align:top; text-align: left;">
                                        {{ $detail->currentRisk->known_issue }}
                                    </td>

                                    <td style="border: none; font-weight: bold; vertical-align:top; width: 150px;">
                                        {{ __('Changing Process & People (15%)') }}</td>
                                    <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                                    <td style="border: none; vertical-align:top; text-align: left;">
                                        {{ $detail->currentRisk->chaning_process }}
                                    </td>
                                </tr>
                                </tr>
                            </table>
                        </li>
                    </ol>
                    <div style="text-align:left;font-weight: bold;">Residual Risk Impact :</div>
                    <ol style="padding-left:20px;" style="list-style-type: lower-alpha">
                        {{-- Impact --}}
                        <li>
                            <table style="border: none; width: 100%">
                                <tr>
                                    <td style="border: none; font-weight: bold; vertical-align:top; width: 150px;">
                                        {{ __('Materiality (40%)') }}</td>
                                    <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                                    <td style="border: none; vertical-align:top; text-align: left;">
                                        {{ $detail->currentRisk->materiality }}</td>

                                    <td style="border: none; font-weight: bold; vertical-align:top; width: 150px;">
                                        {{ __('Operational (30%)') }}</td>
                                    <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                                    <td style="border: none; vertical-align:top; text-align: left;">
                                        {{ $detail->currentRisk->operational }}
                                    </td>
                                </tr>
                            </table>
                        </li>
                        <li>
                            <table style="border: none; width: 100%">
                                <tr>
                                    <td style="border: none; font-weight: bold; vertical-align:top; width: 150px;">
                                        {{ __('Legal & Compliance (30%)') }}</td>
                                    <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                                    <td style="border: none; vertical-align:top; text-align: left;">
                                        {{ $detail->currentRisk->legal }}
                                    </td>
                                </tr>
                            </table>
                        </li>
                    </ol>
                    <br>
                    <div style="text-align:left;font-weight: bold;">Detail Residual Risk :</div>
                    <ol>
                        @foreach ($detail->currentRisk->details as $detailResidual)
                            <li style="margin-left: -1em;">
                                <ol style="list-style-type: lower-alpha">
                                    <li style="margin-left: -1em;">
                                        <table style="border: none; width: 100%">
                                            <tr>
                                                <td
                                                    style="border: none; font-weight: bold; vertical-align:top; width: 135px;">
                                                    {{ __('Internal Control') }}</td>
                                                <td
                                                    style="border: none; vertical-align:top; width: 10px; text-align: left;">
                                                    :</td>
                                                <td style="border: none; vertical-align:top; text-align: left;">
                                                    {{ $detailResidual->internal_control }}
                                                </td>
                                            </tr>
                                        </table>
                                    </li>
                                    <li style="margin-left: -1em;">
                                        <table style="border: none; width: 100%">
                                            <tr>
                                                <td
                                                    style="border: none; font-weight: bold; vertical-align:top; width: 135px;">
                                                    {{ __('Tgl Realisasi') }}</td>
                                                <td
                                                    style="border: none; vertical-align:top; width: 10px; text-align: left;">
                                                    :</td>
                                                <td style="border: none; vertical-align:top; text-align: left;">
                                                    {{ $detailResidual->tgl_realisasi->translatedFormat('d F Y') }}
                                                </td>
                                            </tr>
                                        </table>
                                    </li>
                                    <li style="margin-left: -1em;">
                                        <table style="border: none; width: 100%">
                                            <tr>
                                                <td
                                                    style="border: none; font-weight: bold; vertical-align:top; width: 135px;">
                                                    {{ __('Realisasi') }}</td>
                                                <td
                                                    style="border: none; vertical-align:top; width: 10px; text-align: left;">
                                                    :</td>
                                                <td style="border: none; vertical-align:top; text-align: left;">
                                                    {{ $detailResidual->realisasi }}
                                                </td>
                                            </tr>
                                        </table>
                                    </li>
                                </ol>
                            </li>
                        @endforeach
                    </ol>
                    <br>
                </li>
            @endforeach
        </ol>
        <div style="page-break-inside: avoid;">
            <br><br>
            <div style="text-align: center;">{{ $record->getCityRoot() }},
                {{ $record->updated_at->translatedFormat('d F Y') }}<br>
                {{ __('Menyetujui') }},</div>
            <table style="border:none;">
                <tbody>
                    <tr>
                        @foreach ($record->approval->details as $approval)
                            <td style="border: none; text-align: center; vertical-align: top; width: 33%">
                                @if ($approval->status === 'approved')
                                    <div style="height: 110px; padding-top: 15px; vertical-align: top;">
                                        {!! \Base::getQrcode('Approved by: ' . $approval->user->name . ', ' . $approval->approved_at) !!}
                                    </div>
                                    <div><b><u>{{ $approval->user->name }}</u></b></div>
                                    <div>{{ $approval->position->name }}</div>
                                @else
                                    <div style="height: 110px; padding-top: 15px;; color: #ffffff;">#</div>
                                    <div><b><u>(............................)</u></b></div>
                                    <div>{{ $approval->role->name }}</div>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
            <footer>
                <table table width="100%" border="0" style="border: none;">
                    <tr>
                        <td style="border: none;">
                            <small>
                                
                                <br><i>Tanggal Cetak: {{ now()->translatedFormat('d F Y H:i:s') }} ***Versi
                                    {{ $record->version ?? 0 }}</i>
                            </small>
                        </td>
                    </tr>
                </table>
            </footer>
        </div>
        <br>
        <div style="clear: both"></div>
        <div style="page-break-inside: avoid;">
            <div style="text-align: left;">{{ __('Tembusan') }}:</div>
            <ol>

                <li>Arsip</li>
            </ol>
        </div>
    </main>
</body>

</html>

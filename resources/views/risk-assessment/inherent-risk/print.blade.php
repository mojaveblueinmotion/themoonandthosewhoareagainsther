<html>

<head>
    <title>{{ $title }}</title>
    @include('layouts.partials.print-styles')
</head>
<style>
    body {
        margin-top: 2.2cm;
    }
</style>

<body class="page">
    <header>
        <table style="border:none; width: 100%;">
            <tr>
                <td style="border:none;" width="150px">
                    <img src="{{ getLogo() }}" style="max-width: 150px; max-height: 60px">
                </td>
                <td style="border:none;  text-align: center; font-size: 14pt;" width="auto">
                    <b>{{ __('INHERENT RISK') }}</b>
                </td>
                <td style="border:none; text-align: right;" width="150px">
                    <b></b>
                </td>
            </tr>
        </table>
        <hr>
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
                <td style="border: none; width: 120px;">{{ __('Tipe Audit') }}</td>
                <td style="border: none; width: 10px; text-align: left;">:</td>
                <td style="border: none; text-align: left;">
                    {{ $record->riskRegister->type->name }}
                </td>
            </tr>
            <tr>
                <td style="border: none; width: 120px;">{{ __('Periode') }}</td>
                <td style="border: none; width: 10px; text-align: left;">:</td>
                <td style="border: none; text-align: left;">
                    {{ $record->riskRegister->periode->translatedFormat('Y') }}
                </td>
            </tr>
            <tr>
                <td style="border: none; width: 120px;">{{ __('Subject Audit') }}</td>
                <td style="border: none; width: 10px; text-align: left;">:</td>
                <td style="border: none; text-align: left;">{{ $record->riskRegister->subject->name }}
                </td>
            </tr>
            <tr>
                <td style="vertical-align: top; border: none; width: 120px;">Dept Auditee</td>
                <td style="vertical-align: top; border: none; width: 10px; text-align: left;">:</td>
                <td style="vertical-align: top; border: none; text-align: left;">
                    <div>
                        <ol style="margin: 0px 0px 0px -20px">
                            @foreach ($record->riskRegister->departmentAuditee->departments as $val)
                                <li>{{ $val->name }}</li>
                            @endforeach
                        </ol>
                    </div>
                </td>
            </tr>
        </table>
        <br>
        <div style="text-align:left;font-weight: bold;">Detail Risk Register :</div>
        <ol style="padding-left:20px;" style="list-style-type: lower-alpha">
            <li>
                <table style="border: none; width: 100%">
                    <tr>
                        <td style="border: none; font-weight: bold; width: 150px;">{{ __('Main Process') }}</td>
                        <td style="border: none; width: 10px; text-align: left;">:</td>
                        <td style="border: none; text-align: left;">
                            {{ $record->riskRegisterDetail->kodeResiko->name }}
                        </td>
                    </tr>
                </table>
            </li>
            <li>
                <table style="border: none; width: 100%">
                    <tr>
                        <td style="border: none; font-weight: bold; width: 150px;">{{ __('Sub Process') }}</td>
                        <td style="border: none; width: 10px; text-align: left;">:</td>
                        <td style="border: none; text-align: left;">
                            {{ $record->riskRegisterDetail->id_resiko }} -
                            {{ $record->riskRegisterDetail->jenisResiko->name }}
                        </td>
                    </tr>
                </table>
            </li>
            <li>
                <table style="border: none; width: 100%">
                    <tr>
                        <td style="border: none; font-weight: bold; vertical-align:top; width: 150px;">
                            {{ __('Proses Objective') }}</td>
                        <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                        <td style="border: none; vertical-align:top; text-align: justify;">
                            {{ $record->riskRegisterDetail->objective }}
                        </td>
                    </tr>
                </table>
            </li>
            <li>
                <table style="border: none; width: 100%">
                    <tr>
                        <td style="border: none; font-weight: bold; vertical-align:top; width: 150px;">
                            {{ __('Risk Event') }}</td>
                        <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                        <td style="border: none; vertical-align:top; text-align: justify;">
                            {{ $record->riskRegisterDetail->peristiwa }}
                        </td>
                    </tr>
                </table>
            </li>
            <li>
                <table style="border: none; width: 100%">
                    <tr>
                        <td style="border: none; font-weight: bold; vertical-align:top; width: 150px;">
                            {{ __('Risk Cause') }}</td>
                        <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                        <td style="border: none; vertical-align:top; text-align: justify;">
                            {{ $record->riskRegisterDetail->penyebab }}
                        </td>
                    </tr>
                </table>
            </li>
            <li>
                <table style="border: none; width: 100%">
                    <tr>
                        <td style="border: none; font-weight: bold; vertical-align:top; width: 150px;">
                            {{ __('Risk Impact') }}</td>
                        <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                        <td style="border: none; vertical-align:top; text-align: justify;">
                            {{ $record->riskRegisterDetail->dampak }}
                        </td>
                    </tr>
                </table>
            </li>
        </ol>
        <div style="text-align:left;font-weight: bold;">Detail Likelihood :</div>
        <ol style="padding-left:20px;" style="list-style-type: lower-alpha">
            {{-- Likelihood --}}
            <li>
                <table style="border: none; width: 100%">
                    <tr>
                        <td style="border: none; font-weight: bold; vertical-align:top; width: 150px;">
                            {{ __('Complexity (30%)') }}</td>
                        <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                        <td style="border: none; vertical-align:top; text-align: left;">
                            {{ $record->complexity }}</td>
                    </tr>
                </table>
            </li>
            <li>
                <table style="border: none; width: 100%">
                    <tr>
                        <td style="border: none; font-weight: bold; vertical-align:top; width: 150px;">
                            {{ __('Volume (35%)') }}</td>
                        <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                        <td style="border: none; vertical-align:top; text-align: left;">
                            {{ $record->volume }}
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
                            {{ $record->known_issue }}
                        </td>
                    </tr>
                    </tr>
                </table>
            </li>
            <li>
                <table style="border: none; width: 100%">
                    <tr>
                        <td style="border: none; font-weight: bold; vertical-align:top; width: 150px;">
                            {{ __('Changing Process & People (15%)') }}</td>
                        <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                        <td style="border: none; vertical-align:top; text-align: left;">
                            {{ $record->chaning_process }}
                        </td>
                    </tr>
                    </tr>
                </table>
            </li>
            <li>
                <table style="border: none; width: 100%">
                    <tr>
                        <td style="border: none; font-weight: bold; vertical-align:top; width: 150px;">
                            {{ __('Total Score') }}</td>
                        <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                        <td style="border: none; vertical-align:top; text-align: left;">
                            {{ ($record->complexity * .3) + ($record->volume * .35) + ($record->known_issue * .2) + ($record->chaning_process * .15) }}
                        </td>
                    </tr>
                    </tr>
                </table>
            </li>
        </ol>
        <div style="text-align:left;font-weight: bold;">Detail Impact :</div>
        <ol style="padding-left:20px;" style="list-style-type: lower-alpha">
            {{-- Impact --}}
            <li>
                <table style="border: none; width: 100%">
                    <tr>
                        <td style="border: none; font-weight: bold; vertical-align:top; width: 150px;">
                            {{ __('Materiality (40%)') }}</td>
                        <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                        <td style="border: none; vertical-align:top; text-align: left;">
                            {{ $record->materiality }}</td>
                    </tr>
                </table>
            </li>
            <li>
                <table style="border: none; width: 100%">
                    <tr>
                        <td style="border: none; font-weight: bold; vertical-align:top; width: 150px;">
                            {{ __('Operational (30%)') }}</td>
                        <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                        <td style="border: none; vertical-align:top; text-align: left;">
                            {{ $record->operational }}
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
                            {{ $record->legal }}
                        </td>
                    </tr>
                </table>
            </li>
            <li>
                <table style="border: none; width: 100%">
                    <tr>
                        <td style="border: none; font-weight: bold; vertical-align:top; width: 150px;">
                            {{ __('Total Score') }}</td>
                        <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                        <td style="border: none; vertical-align:top; text-align: left;">
                            {{ ($record->materiality * .4) + ($record->operational * .3) + ($record->legal * .3) }}
                        </td>
                    </tr>
                    </tr>
                </table>
            </li>
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

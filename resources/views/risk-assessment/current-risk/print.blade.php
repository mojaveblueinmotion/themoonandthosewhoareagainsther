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
                    <img src="{{ getLogo() }}" style="max-width: 140px; max-height: 60px">
                </td>
                <td style="border:none;  text-align: center; font-size: 14pt;" width="auto">
                    <b>{{ __('RESIDUAL RISK') }}</b>
                </td>
                <td style="border:none; text-align: right; font-size: 12px;" width="100px">
                    <b></b>
                </td>
                <td style="border:none; display: inline-flex;position: absolute; width: 0px">
                    {{-- @if ($letter->is_available == 'active')
                        <table
                            style="float: right; text-align: center; min-width: 120px; font-size:9pt;margin-top:10px;">
                            <tbody>
                                <tr>
                                    <td style="text-align:center;" colspan="2">Formulir</td>
                                </tr>
                                <tr>
                                    <td style="text-align:center;">{{ $letter->no_formulir }}</td>
                                    <td style="text-align:center;">{{ $letter->no_formulir_tambahan }}</td>
                                </tr>
                            </tbody>
                        </table>
                    @endif --}}
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
                <td style="border:none; width:48%;">
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
                                {{ $record->riskRegisterDetail->riskRegister->periode->translatedFormat('Y') }}
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none; width: 120px;">{{ __('Subject Audit') }}</td>
                            <td style="border: none; width: 10px; text-align: left;">:</td>
                            <td style="border: none; text-align: left;">
                                {{ $record->riskRegisterDetail->riskRegister->subject->name }}</td>
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
                </td>
            </tr>
        </table>
        <br>
        <div style="text-align:left;font-weight: bold;">Detail Risk Register :</div>
        <ol style="list-style-type: lower-alpha; padding-left: 35px;">
            <li>
                <table style="border: none; width: 100%">
                    <tr>
                        <td style="border: none; width: 165px;">{{ __('Main Process') }}</td>
                        <td style="border: none; width: 10px; text-align: left;">:</td>
                        <td style="border: none; text-align: left;">
                            {{ $record->riskRegisterDetail->KodeResiko->name }}
                        </td>
                    </tr>
                </table>
            </li>
            <li>
                <table style="border: none; width: 100%">
                    <tr>
                        <td style="border: none; width: 165px;">{{ __('Sub Process') }}</td>
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
                        <td style="border: none; vertical-align:top; width: 165px;">
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
                        <td style="border: none; vertical-align:top; width: 165px;">
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
                        <td style="border: none; vertical-align:top; width: 165px;">
                            {{ __('Risk Cause') }}</td>
                        <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                        <td style="border: none; vertical-align:top; text-align: justify;">
                            {{ strip_tags($record->riskRegisterDetail->penyebab) }}
                        </td>
                    </tr>
                </table>
            </li>
            <li>
                <table style="border: none; width: 100%">
                    <tr>
                        <td style="border: none; vertical-align:top; width: 165px;">
                            {{ __('Risk Impact') }}</td>
                        <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                        <td style="border: none; vertical-align:top; text-align: justify;">
                            {{ strip_tags($record->riskRegisterDetail->dampak) }}
                        </td>
                    </tr>
                </table>
            </li>
            <li>
                <table style="border: none; width: 100%">
                    <tr>
                        <td style="border: none; vertical-align:top; width: 165px;">
                            {{ __('Condition') }}</td>
                        <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                        <td style="border: none; vertical-align:top; text-align: justify;">
                            {{ strip_tags($record->riskRegisterDetail->condition) }}
                        </td>
                    </tr>
                </table>
            </li>
            <li>
                <table style="border: none; width: 100%">
                    <tr>
                        <td style="border: none; vertical-align:top; width: 165px;">
                            {{ __('Notes') }}</td>
                        <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                        <td style="border: none; vertical-align:top; text-align: justify;">
                            {{ strip_tags($record->riskRegisterDetail->notes) }}
                        </td>
                    </tr>
                </table>
            </li>

        </ol>
        <div style="text-align:left;font-weight: bold;">Inherent Risk :</div>
        <div style="text-align:left;font-weight: bold;">Likelihood :</div>

        <table style="border: none; padding-left: 15px; width: 100%">
            <tr>
                <td style="border: none; vertical-align:top; width: 185px;">
                    a. {{ __('Complexity (30%)') }}
                </td>
                <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                <td style="border: none; vertical-align:top; width: 150px;text-align: left;">
                    {{ $record->riskRegisterDetail->inherentRisk->complexity }}
                </td>
                <td style="border: none; vertical-align:top; width: 240px;">
                    d. {{ __('Changing Process & People (15%)') }}</td>
                <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                <td style="border: none; vertical-align:top; text-align: left;">
                    {{ $record->riskRegisterDetail->inherentRisk->chaning_process }}
                </td>
            </tr>
            <tr>
                <td style="border: none; vertical-align:top; width: 150px;">
                    b. {{ __('Volume (35%)') }}
                </td>
                <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                <td style="border: none; vertical-align:top; text-align: left;">
                    {{ $record->riskRegisterDetail->inherentRisk->volume }}
                </td>

                <td style="border: none; vertical-align:top; width: 240px;">
                    e. {{ __('Total Score') }}
                </td>
                <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                <td style="border: none; vertical-align:top; text-align: left;">
                    {{ ($record->riskRegisterDetail->inherentRisk->complexity * .3) + ($record->riskRegisterDetail->inherentRisk->volume * .35) + ($record->riskRegisterDetail->inherentRisk->known_issue * .2) + ($record->riskRegisterDetail->inherentRisk->chaning_process * .15) }}
                </td>
            </tr>
            <tr>
                <td style="border: none; vertical-align:top; width: 150px;">
                    c. {{ __('Known Issue (20%)') }}
                </td>
                <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                <td style="border: none; vertical-align:top; text-align: left;">
                    {{ $record->riskRegisterDetail->inherentRisk->known_issue }}
                </td>

            </tr>
        </table>
        <div style="text-align:left;font-weight: bold;">Risk Impact :</div>
        <table style="border: none; padding-left: 15px; width: 100%">
            <tr>
                <td style="border: none; vertical-align:top; width: 185px;">
                    a. {{ __('Materiality (40%)') }}
                </td>
                <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                <td style="border: none; vertical-align:top; width: 150px; text-align: left;">
                    {{ $record->riskRegisterDetail->inherentRisk->materiality }}
                </td>
                <td style="border: none; vertical-align:top; width: 150px;">
                    c. {{ __('Operational (30%)') }}
                </td>
                <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                <td style="border: none; vertical-align:top; text-align: left;">
                    {{ $record->riskRegisterDetail->inherentRisk->operational }}
                </td>
            </tr>
            <tr>
                <td style="border: none; vertical-align:top; width: 150px;">
                    b. {{ __('Legal & Compliance (30%)') }}
                </td>
                <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                <td style="border: none; vertical-align:top; text-align: left;">
                    {{ $record->riskRegisterDetail->inherentRisk->legal }}
                </td>
                <td style="border: none; vertical-align:top; width: 150px;">
                    d. {{ __('Total Score') }}
                </td>
                <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                <td style="border: none; vertical-align:top; text-align: left;">
                    {{ ($record->riskRegisterDetail->inherentRisk->materiality * .4) + ($record->riskRegisterDetail->inherentRisk->operational * .3) + ($record->riskRegisterDetail->inherentRisk->legal * .3) }}
                </td>
            </tr>
        </table>
        <br>

        <div style="text-align:left;font-weight: bold;">Residual Risk :</div>
        <div style="text-align:left;font-weight: bold;">Likelihood :</div>
        <table style="border: none; padding-left: 15px; width: 100%">
            <tr>
                <td style="border: none; vertical-align:top; width: 185px;">
                    a. {{ __('Complexity (30%)') }}
                </td>
                <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                <td style="border: none; vertical-align:top; width: 150px; text-align: left;">
                    {{ $record->complexity }}
                </td>
                <td style="border: none; vertical-align:top; width: 150px;">
                    d. {{ __('Volume (35%)') }}
                </td>
                <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                <td style="border: none; vertical-align:top; text-align: left;">
                    {{ $record->volume }}
                </td>
            </tr>
            <tr>
                <td style="border: none; vertical-align:top; width: 185px;">
                    b. {{ __('Known Issue (20%)') }}
                </td>
                <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                <td style="border: none; vertical-align:top; text-align: left;">
                    {{ $record->known_issue }}
                </td>

                <td style="border: none; vertical-align:top; width: 150px;">
                    e. {{ __('Total Score') }}</td>
                <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                <td style="border: none; vertical-align:top; text-align: left;">
                    {{ ($record->complexity * .3) + ($record->known_issue * .2) + ($record->chaning_process * .15) + ($record->volume * .35) }}
                </td>
            </tr>
            <tr>
                <td style="border: none; vertical-align:top; width: 185px;">
                    c. {{ __('Changing Process & People (15%)') }}
                </td>
                <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                <td style="border: none; vertical-align:top; text-align: left;">
                    {{ $record->chaning_process }}
                </td>
            </tr>
        </table>
        <div style="text-align:left;font-weight: bold;">Risk Impact :</div>
        <table style="border: none; padding-left: 15px; width: 100%;">
            <tr>
                <td style="border: none; vertical-align:top; width: 185px;">
                    a. {{ __('Materiality (40%)') }}
                </td>
                <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                <td style="border: none; vertical-align:top; text-align: left; width: 150px">
                    {{ $record->materiality }}
                </td>
                <td style="border: none; vertical-align:top; width: 150px;">
                    c. {{ __('Operational (30%)') }}
                </td>
                <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                <td style="border: none; vertical-align:top; text-align: left;">
                    {{ $record->operational }}
                </td>
            </tr>
            <tr>
                <td style="border: none; vertical-align:top; width: 185px;">
                    b. {{ __('Legal & Compliance (30%)') }}
                </td>
                <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                <td style="border: none; vertical-align:top; text-align: left;">
                    {{ $record->legal }}
                </td>
                <td style="border: none; vertical-align:top; width: 150px;">
                    d. {{ __('Total Score') }}
                </td>
                <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                <td style="border: none; vertical-align:top; text-align: left;">
                    {{ ($record->materiality * .4)+($record->operational * .3)+($record->legal * .3) }}
                </td>
            </tr>
        </table>
        <br>

        <div style="page-break-inside: avoid;">
            <br>
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

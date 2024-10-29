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
                    <b>{{ __('RISK REGISTER') }}</b>
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
                                {{ $record->type->name }}
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none; width: 120px;">{{ __('Periode') }}</td>
                            <td style="border: none; width: 10px; text-align: left;">:</td>
                            <td style="border: none; text-align: left;">
                                {{ $record->periode->translatedFormat('Y') }}
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none; width: 120px;">{{ __('Subject Audit') }}</td>
                            <td style="border: none; width: 10px; text-align: left;">:</td>
                            <td style="border: none; text-align: left;">{{ $record->subject->name }}</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top; border: none; width: 120px;">Dept Auditee</td>
                            <td style="vertical-align: top; border: none; width: 10px; text-align: left;">:</td>
                            <td style="vertical-align: top; border: none; text-align: left;">
                                <div>
                                    <ol style="margin: 0px 0px 0px -20px">
                                        @foreach ($record->departmentAuditee->departments as $val)
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
        <div style="page-break-inside: auto;">
            <div style="text-align:left;font-weight: bold;">Detail Risk Register :</div>
            <ol style="padding-left:20px;">
                @foreach ($record->details()->orderBy('created_at', 'DESC')->orderBy('updated_at', 'DESC')->get() as $part)
                    <li>
                        <ol style="list-style-type: lower-alpha; padding-left:20px;">
                            <li>
                                <table style="border: none; width: 100%">
                                    <tr>
                                        <td style="border: none; vertical-align: top; font-weight: bold; width: 120px;">
                                            Main Process</td>
                                        <td style="border: none; vertical-align: top; width: 10px;">:</td>
                                        <td style="border: none; vertical-align: top; text-align:justify">
                                            {{ $part->kodeResiko->name }}
                                        </td>
                                    </tr>
                                </table>
                            </li>
                            <li>
                                <table style="border: none; width: 100%">
                                    <tr>
                                        <td style="border: none; vertical-align: top; font-weight: bold; width: 120px;">
                                            Sub Process</td>
                                        <td style="border: none; vertical-align: top; width: 10px;">:</td>
                                        <td style="border: none; vertical-align: top; text-align:justify">
                                            {{ $part->id_resiko }} -
                                            {{ $part->jenisResiko->name }}
                                        </td>
                                    </tr>
                                </table>
                            </li>
                            <li>
                                <table style="border: none; width: 100%">
                                    <tr>
                                        <td style="border: none; font-weight: bold; vertical-align:top; width: 120px;">
                                            {{ __('Proses Objective') }}</td>
                                        <td style="border: none; vertical-align:top; width: 10px; text-align: left;">:</td>
                                        <td style="border: none; vertical-align:top; text-align: justify;">
                                            {{ $part->objective }}
                                        </td>
                                    </tr>
                                </table>
                            </li>
                            <li>
                                <table style="border: none; width: 100%">
                                    <tr>
                                        <td style="border: none; vertical-align: top; font-weight: bold; width: 120px;">
                                            Risk Event</td>
                                        <td style="border: none; vertical-align: top; width: 10px;">:</td>
                                        <td style="border: none; vertical-align: top; text-align:justify">
                                            {{ $part->peristiwa }}
                                        </td>
                                    </tr>
                                </table>
                            </li>
                            <li>
                                <table style="border: none; width: 100%">
                                    <tr>
                                        <td style="border: none; vertical-align: top; font-weight: bold; width: 120px;">
                                            Risk Cause</td>
                                        <td style="border: none; vertical-align: top; width: 10px;">:</td>
                                        <td style="border: none; vertical-align: top; text-align:justify">
                                            {{ $part->penyebab }}
                                        </td>
                                    </tr>
                                </table>
                            </li>
                            <li>
                                <table style="border: none; width: 100%">
                                    <tr>
                                        <td style="border: none; vertical-align: top; font-weight: bold; width: 120px;">
                                            Risk Impact</td>
                                        <td style="border: none; vertical-align: top; width: 10px;">:</td>
                                        <td style="border: none; vertical-align: top; text-align:justify">
                                            {{ $part->dampak }}
                                        </td>
                                    </tr>
                                </table>
                            </li>
                            <br>
                        </ol>
                    </li>
                @endforeach
            </ol>
        </div>
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

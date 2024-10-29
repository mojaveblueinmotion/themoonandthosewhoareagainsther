<html>

<head>
    <title>{{ $title }}</title>
    @include('layouts.partials.print-styles')
</head>

<body class="page">
    <header>
        <table class="table-header" style="border:1px solid #002060; width: 100%; height: 100px;">
            <tr>
                <td style="border:none; background-color: #d9e2f3; padding:5px 5px 5px 5px;" width="60px">
                    <img src="{{ getLogo() }}" style="width: 60px; height: 90px;">
                </td>
                <td style="border:none; padding:5px 30px 5px 5px; color: #002060; background-color: #d9e2f3;"
                    width="auto">
                    <p style="font-size: 16px; font-weight:bold; line-height: 5px;">{!! __('SPI - Gunung Madu Plantations') !!}</p>
                    <p style="font-size: 12px; line-height: 15px; font-style: italic;">
                        {!! __('Visi Perusahaan :') !!}<br>
                        {!! __(
                            'Menjadi produsen gula yang paling efisien dan kompetitif di ASEAN dengan menerapkan sistem pertanian berkelanjutan',
                        ) !!}
                    </p>
                </td>
                <td style="border:1px solid #002060; text-align: center; justify-content:center font-size: 10px; background; color:white; background-color: #44546a;"
                    width="150px">
                    <p style="font-size: 14px; font-weight: bold; text-transform: uppercase">{!! __($TITLE) !!}</p>
                    @if (isset($letter) && $letter->is_available == 'active')
                        <hr class="horizontal-line" style="border:1px solid #fff;">
                        <p style="font-size: 10px;">
                            {{ $letter->no_formulir }} | {{ $letter->no_formulir_tambahan }}
                        </p>
                    @endif
                </td>
            </tr>
        </table>
    </header>
    <footer>
        <table table width="100%" border="0" style="border: none;">
            <tr>
                <td style="border: none;">
                    <small>
                        <br><i>Tanggal Cetak: {{ now()->setTimezone('Asia/Jakarta')->translatedFormat('d F Y H:i:s') }}
                            ***Versi: {{ $record->version ?? 0 }}</i>
                    </small>
                </td>
            </tr>
        </table>
    </footer>
    <main>
        <table style="border:none;">
            <tr>
                <td style="border:none; text-align:center;">
                    <div>Nomor: {{ $record->show_letter_no }}</div>
                </td>
            </tr>
            <tr>
                <td style="border:none;">
                    <div>Kepada:</div>
                    @foreach ($record->users as $user)
                        <div>{{ $user->name }}</div>
                    @endforeach
                    <div>{{ getRoot()->name }}</div>
                    <div style="white-space: pre-wrap;">{{ $record->to_address }}</div>
                </td>
            </tr>
        </table>
        <br>
        <div style="white-space: pre-wrap; text-indent: 50px; text-align: justify;">{!! $record->description !!}</div>
        <div style="margin-top: 10px; margin-left: 30px">
            <table style="border:none; width: 100%;">
                <tr>
                    <td style="border: none; vertical-align:top; width: 20px;">1.</td>
                    <td style="border: none; vertical-align:top; width: 300px;">Penanggungjawab merangkap Pengawas</td>
                    <td style="border: none; vertical-align:top; width: 10px;">:</td>
                    <td style="border: none; vertical-align:top;">{{ $record->pic->name }}</td>
                </tr>
                <tr>
                    <td style="border: none; vertical-align:top; width: 20px;">2.</td>
                    <td style="border: none; vertical-align:top; width: 300px;">Ketua Tim merangkap Anggota</td>
                    <td style="border: none; vertical-align:top; width: 10px;">:</td>
                    <td style="border: none; vertical-align:top;">{{ $record->leader->name }}</td>
                </tr>
                <tr>
                    <td style="border: none; vertical-align:top; width: 20px;">3.</td>
                    <td style="border: none; vertical-align:top; width: 300px;">Anggota</td>
                    <td style="border: none; vertical-align:top; width: 10px;">:</td>
                    <td style="border: none; vertical-align:top;">
                        <ol style="margin: 0; padding-left: 20px;">
                            @foreach ($record->members as $user)
                                <li>{{ $user->name }}</li>
                            @endforeach
                        </ol>
                    </td>
                </tr>
            </table>
        </div>
        <br>
        <div style="white-space: pre-wrap; text-indent: 50px; text-align: justify;">{!! $record->note !!}
            {{ $record->date_start->setTimezone('Asia/Jakarta')->translatedFormat('d F Y') . ' ' . $record->date_end->setTimezone('Asia/Jakarta')->translatedFormat('d F Y') }}<br>
            {{ __('Menyetujui') }},</div>
        <br>
        <br>

        <div style="page-break-inside: avoid;">
            <div style="text-align: center;">{{ getCompanyCity() }},
                {{ $record->letter_date->translatedFormat('d F Y') }}<br>
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
        </div>
        <br>
        <div style="clear: both"></div>
        <div style="page-break-inside: avoid;">
            <div style="text-align: left;">{{ __('Tembusan') }}:</div>
            <ol>
                @if ($record->cc()->exists())
                    @foreach ($record->cc()->get() as $cc)
                        <li>Yth. {{ $cc->position->name }}</li>
                    @endforeach
                @else
                @endif
                <li>Arsip</li>
            </ol>
        </div>
    </main>
</body>

</html>

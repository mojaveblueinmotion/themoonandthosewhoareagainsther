@extends('layouts.page', ['container' => 'container'])

@section('card-body')
    @include('globals.header')
    <hr>
    <div class="col-md-12 parent-group">
        <div class="table-responsive">
            <table class="table-bordered mb-1 table">
                <thead>
                    <tr>
                        <th class="width-40px text-center">#</th>
                        <th class="text-center">{{ __('Menu') }}</th>
                        <th class="text-center">{{ __('Status') }}</th>
                        <th class="text-center">{{ __('Tanggal Mulai') }}</th>
                        <th class="text-center">{{ __('Tanggal Selesai') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $iteration = 1;
                    @endphp
                    {{-- @dump($summary->riskRegister) --}}
                    <tr>
                        <td></td>
                        <td class="parent-group text-left" colspan="3">
                            <b>Risk Assesment</b>
                        </td>
                    </tr>
                    @if ($riskRegister = $summary->riskRegister)
                        <tr>
                            <td class="width-40px text-center">{{ $iteration++ }}</td>
                            <td class="parent-group text-left">
                                Risk Register
                            </td>
                            <td class="parent-group text-center">
                                {!! \Base::getStatus($riskRegister->status) !!}
                            </td>
                            <td class="text-center">
                                {{ $riskRegister->created_at ? \Base::dateFormat($riskRegister->created_at) : '-' }}
                            </td>
                            <td class="parent-group text-center">
                                {{ $riskRegister->updated_at ? \Base::dateFormat($riskRegister->updated_at) : '-' }}
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td class="width-40px text-center">{{ $iteration++ }}</td>
                            <td class="parent-group text-left">
                                Risk Register
                            </td>
                            <td class="parent-group text-center">
                                -
                            </td>
                            <td class="text-center">
                                -
                            </td>
                            <td class="parent-group text-center">
                                -
                            </td>
                        </tr>
                    @endif
                    @if ($riskRegister->inherentRisk->isNotEmpty())
                        @foreach ($summary->riskRegister->inherentRisk as $value)
                            <tr>
                                <td class="width-40px text-center">{{ $iteration++ }}</td>
                                <td class="parent-group text-left">
                                    Inherent Risk - {{ $value->riskRegisterDetail->id_resiko }}
                                </td>
                                <td class="parent-group text-center">
                                    {!! \Base::getStatus($value->status) !!}
                                </td>
                                <td class="text-center">
                                    {{ $value->created_at ? \Base::dateFormat($value->created_at) : '-' }}
                                </td>
                                <td class="parent-group text-center">
                                    {{ $value->updated_at ? \Base::dateFormat($value->updated_at) : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="width-40px text-center">{{ $iteration++ }}</td>
                            <td class="parent-group text-left">
                                Inherent Risk
                            </td>
                            <td class="parent-group text-center">
                                -
                            </td>
                            <td class="text-center">
                                -
                            </td>
                            <td class="parent-group text-center">
                                -
                            </td>
                        </tr>
                    @endif
                    @if ($summary->riskRegister->residualRisk->isNotEmpty())
                        @foreach ($summary->riskRegister->residualRisk as $value)
                            <tr>
                                <td class="width-40px text-center">{{ $iteration++ }}</td>
                                <td class="parent-group text-left">
                                    Residual Risk - {{ $value->riskRegisterDetail->id_resiko }}
                                </td>
                                <td class="parent-group text-center">
                                    {!! \Base::getStatus($value->status) !!}
                                </td>
                                <td class="text-center">
                                    {{ $value->created_at ? \Base::dateFormat($value->created_at) : '-' }}
                                </td>
                                <td class="parent-group text-center">
                                    {{ $value->updated_at ? \Base::dateFormat($value->updated_at) : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="width-40px text-center">{{ $iteration++ }}</td>
                            <td class="parent-group text-left">
                                Residual Risk
                            </td>
                            <td class="parent-group text-center">
                                -
                            </td>
                            <td class="text-center">
                                -
                            </td>
                            <td class="parent-group text-center">
                                -
                            </td>
                        </tr>
                    @endif

                    @if ($summary->rkia_id != null)
                        <tr>
                            <td></td>
                            <td class="parent-group text-left" colspan="3">
                                <b>Audit Plan</b>
                            </td>
                        </tr>
                        <tr>
                            <td class="width-40px text-center">{{ $iteration++ }}</td>
                            <td class="parent-group text-left">
                                Audit Plan
                            </td>
                            <td class="parent-group text-center">
                                {!! \Base::getStatus($summary->rkia->status) !!}
                            </td>
                            <td class="text-center">
                                {{ $summary->rkia->created_at ? \Base::dateFormat($summary->rkia->created_at) : '-' }}
                            </td>
                            <td class="parent-group text-center">
                                {{ $summary->rkia->updated_at ? \Base::dateFormat($summary->rkia->updated_at) : '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="parent-group text-left" colspan="3">
                                <b>Persiapan Audit</b>
                            </td>
                        </tr>
                        @if ($assignment = $summary->assignment()->first())
                            <tr>
                                <td class="width-40px text-center">{{ $iteration++ }}</td>
                                <td class="parent-group text-left">
                                    Surat Penugasan
                                </td>
                                <td class="parent-group text-center">
                                    {!! \Base::getStatus($assignment->status) !!}
                                </td>
                                <td class="text-center">
                                    {{ $assignment->created_at ? \Base::dateFormat($assignment->created_at) : '-' }}
                                </td>
                                <td class="parent-group text-center">
                                    {{ $assignment->updated_at ? \Base::dateFormat($assignment->updated_at) : '-' }}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="width-40px text-center">{{ $iteration++ }}</td>
                                <td class="parent-group text-left">
                                    Surat Penugasan
                                </td>
                                <td class="parent-group text-center">
                                    -
                                </td>
                                <td class="text-center">
                                    -
                                </td>
                                <td class="parent-group text-center">
                                    -
                                </td>
                            </tr>
                        @endif
                    @endif
                    @if ($summary->instruction)
                        <tr>
                            <td></td>
                            <td class="parent-group text-left" colspan="3">
                                <b>Persiapan Audit</b>
                            </td>
                        </tr>
                        @if ($instruction = $summary->instruction()->first())
                            <tr>
                                <td class="width-40px text-center">{{ $iteration++ }}</td>
                                <td class="parent-group text-left">
                                    Instruksi Audit
                                </td>
                                <td class="parent-group text-center">
                                    {!! \Base::getStatus($instruction->status) !!}
                                </td>
                                <td class="text-center">
                                    {{ $instruction->created_at ? \Base::dateFormat($instruction->created_at) : '-' }}
                                </td>
                                <td class="parent-group text-center">
                                    {{ $instruction->updated_at ? \Base::dateFormat($instruction->updated_at) : '-' }}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="width-40px text-center">{{ $iteration++ }}</td>
                                <td class="parent-group text-left">
                                    Instruksi Audit
                                </td>
                                <td class="parent-group text-center">
                                    -
                                </td>
                                <td class="text-center">
                                    -
                                </td>
                                <td class="parent-group text-center">
                                    -
                                </td>
                            </tr>
                        @endif
                    @endif
                    @if ($program = $summary->apm()->first())
                        <tr>
                            <td class="width-40px text-center">{{ $iteration++ }}</td>
                            <td class="parent-group text-left">
                                Program Audit
                            </td>
                            <td class="parent-group text-center">
                                {!! \Base::getStatus($program->status) !!}
                            </td>
                            <td class="text-center">
                                {{ $program->created_at ? \Base::dateFormat($program->created_at) : '-' }}
                            </td>
                            <td class="parent-group text-center">
                                {{ $program->updated_at ? \Base::dateFormat($program->updated_at) : '-' }}
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td class="width-40px text-center">{{ $iteration++ }}</td>
                            <td class="parent-group text-left">
                                Program Audit
                            </td>
                            <td class="parent-group text-center">
                                -
                            </td>
                            <td class="text-center">
                                -
                            </td>
                            <td class="parent-group text-center">
                                -
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td></td>
                        <td class="parent-group text-left" colspan="3">
                            <b>Pelaksanaan Audit</b>
                        </td>
                    </tr>
                    @if ($memoOpening = $summary->memoOpening()->first())
                        <tr>
                            <td class="width-40px text-center">{{ $iteration++ }}</td>
                            <td class="parent-group text-left">
                                Memo Opening
                            </td>
                            <td class="parent-group text-center">
                                {!! \Base::getStatus($memoOpening->status) !!}
                            </td>
                            <td class="text-center">
                                {{ $memoOpening->created_at ? \Base::dateFormat($memoOpening->created_at) : '-' }}
                            </td>
                            <td class="parent-group text-center">
                                {{ $memoOpening->updated_at ? \Base::dateFormat($memoOpening->updated_at) : '-' }}
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td class="width-40px text-center">{{ $iteration++ }}</td>
                            <td class="parent-group text-left">
                                Memo Opening
                            </td>
                            <td class="parent-group text-center">
                                -
                            </td>
                            <td class="text-center">
                                -
                            </td>
                            <td class="parent-group text-center">
                                -
                            </td>
                        </tr>
                    @endif
                    @if ($opening = $summary->opening()->first())
                        <tr>
                            <td class="width-40px text-center">{{ $iteration++ }}</td>
                            <td class="parent-group text-left">
                                Opening Meeting
                            </td>
                            <td class="parent-group text-center">
                                {!! \Base::getStatus($opening->status) !!}
                            </td>
                            <td class="text-center">
                                {{ $opening->created_at ? \Base::dateFormat($opening->created_at) : '-' }}
                            </td>
                            <td class="parent-group text-center">
                                {{ $opening->updated_at ? \Base::dateFormat($opening->updated_at) : '-' }}
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td class="width-40px text-center">{{ $iteration++ }}</td>
                            <td class="parent-group text-left">
                                Opening Meeting
                            </td>
                            <td class="parent-group text-center">
                                -
                            </td>
                            <td class="text-center">
                                -
                            </td>
                            <td class="parent-group text-center">
                                -
                            </td>
                        </tr>
                    @endif
                    {{-- samples --}}
                    {{-- @dump($summary->samples->isNotEmpty()) --}}
                    @if ($summary->samples->isNotEmpty())
                        @foreach ($summary->samples as $sample)
                            @php
                                $pieces = explode(' ', $sample->agenda->procedure);
                                $langkah = implode(' ', array_splice($pieces, 0, 3));
                            @endphp
                            @if ($opening = $summary->opening()->first())
                                <tr>
                                    <td class="width-40px text-center">{{ $iteration++ }}</td>
                                    <td class="parent-group text-left">
                                        Kertas Kerja ({{ $langkah }})
                                        {{ $sample->no_kka ? '- ' . $sample->no_kka : '-' }}
                                    </td>
                                    <td class="parent-group text-center">
                                        {!! \Base::getStatus($sample->status) !!}
                                    </td>
                                    <td class="text-center">
                                        {{ $sample->status != 'new' && $sample->created_at ? \Base::dateFormat($sample->created_at) : '-' }}
                                    </td>
                                    <td class="parent-group text-center">
                                        {{ $sample->updated_at ? \Base::dateFormat($sample->updated_at) : '-' }}
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td class="width-40px text-center">{{ $iteration++ }}</td>
                                    <td class="parent-group text-left">
                                        Kertas Kerja
                                    </td>
                                    <td class="parent-group text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="parent-group text-center">
                                        -
                                    </td>
                                </tr>
                            @endif

                            @if ($reviewSample = $sample->reviewSample)
                                <tr>
                                    <td class="width-40px text-center">{{ $iteration++ }}</td>
                                    <td class="parent-group text-left">
                                        Review Kertas Kerja ({{ $langkah }}) -
                                        {{ $sample->no_kka }}
                                    </td>
                                    <td class="parent-group text-center">
                                        {!! \Base::getStatus($reviewSample->status) !!}
                                    </td>
                                    <td class="text-center">
                                        {{ $reviewSample->status != 'new' && $reviewSample->created_at ? \Base::dateFormat($reviewSample->created_at) : '-' }}
                                    </td>
                                    <td class="parent-group text-center">
                                        {{ $reviewSample->updated_at ? \Base::dateFormat($reviewSample->updated_at) : '-' }}
                                    </td>
                                </tr>
                            @endif
                            @forelse ($sample->details as $detail)
                                @if ($feedback = $detail->feedback)
                                    <tr>
                                        <td class="width-40px text-center">{{ $iteration++ }}</td>
                                        <td class="parent-group text-left">
                                            Tanggapan ({{ $langkah }}) -
                                            {{ $detail->id_temuan }}
                                        </td>
                                        <td class="parent-group text-center">
                                            {!! \Base::getStatus($feedback->status) !!}
                                        </td>
                                        <td class="text-center">
                                            {{ $feedback->status != 'new' && $feedback->created_at ? \Base::dateFormat($feedback->created_at) : '-' }}
                                        </td>
                                        <td class="parent-group text-center">
                                            {{ $feedback->updated_at ? \Base::dateFormat($feedback->updated_at) : '-' }}
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td class="width-40px text-center">{{ $iteration++ }}</td>
                                        <td class="parent-group text-left">
                                            Tanggapan ({{ $langkah }}) -
                                            {{ $detail->id_temuan }}
                                        </td>
                                        <td class="parent-group text-center">
                                            -
                                        </td>
                                        <td class="text-center">
                                            -
                                        </td>
                                        <td class="parent-group text-center">
                                            -
                                        </td>
                                    </tr>
                                @endif
                                @if ($worksheet = $detail->worksheet)
                                    <tr>
                                        <td class="width-40px text-center">{{ $iteration++ }}</td>
                                        <td class="parent-group text-left">
                                            Opini & Rekomendasi ({{ $langkah }}) -
                                            {{ $detail->id_temuan }}
                                        </td>
                                        <td class="parent-group text-center">
                                            {!! \Base::getStatus($worksheet->status) !!}
                                        </td>
                                        <td class="text-center">
                                            {{ $worksheet->status != 'new' && $worksheet->created_at ? \Base::dateFormat($worksheet->created_at) : '-' }}
                                        </td>
                                        <td class="parent-group text-center">
                                            {{ $worksheet->updated_at ? \Base::dateFormat($worksheet->updated_at) : '-' }}
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td class="width-40px text-center">{{ $iteration++ }}</td>
                                        <td class="parent-group text-left">
                                            Opnini & Rekomendasi ({{ $langkah }}) -
                                            {{ $detail->id_temuan }}
                                        </td>
                                        <td class="parent-group text-center">
                                            -
                                        </td>
                                        <td class="text-center">
                                            -
                                        </td>
                                        <td class="parent-group text-center">
                                            -
                                        </td>
                                    </tr>
                                @endif
                                @if ($commitment = $detail->komitmen)
                                    <tr>
                                        <td class="width-40px text-center">{{ $iteration++ }}</td>
                                        <td class="parent-group text-left">
                                            Komentar Manajemen ({{ $langkah }}) -
                                            {{ $detail->id_temuan }}
                                        </td>
                                        <td class="parent-group text-center">
                                            {!! \Base::getStatus($commitment->status) !!}
                                        </td>
                                        <td class="text-center">
                                            {{ $commitment->status != 'new' && $commitment->created_at ? \Base::dateFormat($commitment->created_at) : '-' }}
                                        </td>
                                        <td class="parent-group text-center">
                                            {{ $commitment->updated_at ? \Base::dateFormat($commitment->updated_at) : '-' }}
                                        </td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td class="width-40px text-center">{{ $iteration++ }}</td>
                                        <td class="parent-group text-left">
                                            Komentar Manajemen ({{ $langkah }}) -
                                            {{ $detail->id_temuan }}
                                        </td>
                                        <td class="parent-group text-center">
                                            -
                                        </td>
                                        <td class="text-center">
                                            -
                                        </td>
                                        <td class="parent-group text-center">
                                            -
                                        </td>
                                    </tr>
                                @endif
                            @empty
                            @endforelse
                        @endforeach
                    @else
                        <tr>
                            <td class="width-40px text-center">{{ $iteration++ }}</td>
                            <td class="parent-group text-left">
                                Kertas Kerja
                            </td>
                            <td class="parent-group text-center">
                                -
                            </td>
                            <td class="text-center">
                                -
                            </td>
                            <td class="parent-group text-center">
                                -
                            </td>
                        </tr>
                        <tr>
                            <td class="width-40px text-center">{{ $iteration++ }}</td>
                            <td class="parent-group text-left">
                                Review Kertas Kerja
                            </td>
                            <td class="parent-group text-center">
                                -
                            </td>
                            <td class="text-center">
                                -
                            </td>
                            <td class="parent-group text-center">
                                -
                            </td>
                        </tr>
                        <tr>
                            <td class="width-40px text-center">{{ $iteration++ }}</td>
                            <td class="parent-group text-left">
                                Tanggapan
                            </td>
                            <td class="parent-group text-center">
                                -
                            </td>
                            <td class="text-center">
                                -
                            </td>
                            <td class="parent-group text-center">
                                -
                            </td>
                        </tr>
                        <tr>
                            <td class="width-40px text-center">{{ $iteration++ }}</td>
                            <td class="parent-group text-left">
                                Opini & Rekomendasi
                            </td>
                            <td class="parent-group text-center">
                                -
                            </td>
                            <td class="text-center">
                                -
                            </td>
                            <td class="parent-group text-center">
                                -
                            </td>
                        </tr>
                        <tr>
                            <td class="width-40px text-center">{{ $iteration++ }}</td>
                            <td class="parent-group text-left">
                                Komentar Manajemen
                            </td>
                            <td class="parent-group text-center">
                                -
                            </td>
                            <td class="text-center">
                                -
                            </td>
                            <td class="parent-group text-center">
                                -
                            </td>
                        </tr>
                    @endif

                    @if ($memoClosing = $summary->memoClosing()->first())
                        <tr>
                            <td class="width-40px text-center">{{ $iteration++ }}</td>
                            <td class="parent-group text-left">
                                Memo Closing
                            </td>
                            <td class="parent-group text-center">
                                {!! \Base::getStatus($memoClosing->status) !!}
                            </td>
                            <td class="text-center">
                                {{ $memoClosing->created_at ? \Base::dateFormat($memoClosing->created_at) : '-' }}
                            </td>
                            <td class="parent-group text-center">
                                {{ $memoClosing->updated_at ? \Base::dateFormat($memoClosing->updated_at) : '-' }}
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td class="width-40px text-center">{{ $iteration++ }}</td>
                            <td class="parent-group text-left">
                                Memo Closing
                            </td>
                            <td class="parent-group text-center">
                                -
                            </td>
                            <td class="text-center">
                                -
                            </td>
                            <td class="parent-group text-center">
                                -
                            </td>
                        </tr>
                    @endif
                    @if ($closing = $summary->closing()->first())
                        <tr>
                            <td class="width-40px text-center">{{ $iteration++ }}</td>
                            <td class="parent-group text-left">
                                Closing Meeting
                            </td>
                            <td class="parent-group text-center">
                                {!! \Base::getStatus($closing->status) !!}
                            </td>
                            <td class="text-center">
                                {{ $closing->created_at ? \Base::dateFormat($closing->created_at) : '-' }}
                            </td>
                            <td class="parent-group text-center">
                                {{ $closing->updated_at ? \Base::dateFormat($closing->updated_at) : '-' }}
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td class="width-40px text-center">{{ $iteration++ }}</td>
                            <td class="parent-group text-left">
                                Closing Meeting
                            </td>
                            <td class="parent-group text-center">
                                -
                            </td>
                            <td class="text-center">
                                -
                            </td>
                            <td class="parent-group text-center">
                                -
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td></td>
                        <td class="parent-group text-left" colspan="4">
                            <b>Pelaporan Audit</b>
                        </td>
                    </tr>
                    @if ($lha = $summary->lha()->first())
                        <tr>
                            <td class="width-40px text-center">{{ $iteration++ }}</td>
                            <td class="parent-group text-left">
                                LHP
                            </td>
                            <td class="parent-group text-center">
                                {!! \Base::getStatus($lha->status) !!}
                            </td>
                            <td class="text-center">
                                {{ $lha->created_at ? \Base::dateFormat($lha->created_at) : '-' }}
                            </td>
                            <td class="parent-group text-center">
                                {{ $lha->updated_at ? \Base::dateFormat($lha->updated_at) : '-' }}
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td class="width-40px text-center">{{ $iteration++ }}</td>
                            <td class="parent-group text-left">
                                LHP
                            </td>
                            <td class="parent-group text-center">
                                -
                            </td>
                            <td class="text-center">
                                -
                            </td>
                            <td class="parent-group text-center">
                                -
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td></td>
                        <td class="parent-group text-left" colspan="3">
                            <b>Tindak Lanjut Audit</b>
                        </td>
                    </tr>
                    @forelse (\App\Models\Followup\MemoTindakLanjut::where('summary_id', $summary->id)->get() as $followupMemoTindakLanjut)
                        <tr>
                            <td class="width-40px text-center">{{ $iteration++ }}</td>
                            <td class="parent-group text-left">
                                Memo Tindak Lanjut ({{ $followupMemoTindakLanjut->reg->struct->name }})
                            </td>
                            <td class="parent-group text-center">
                                {!! \Base::getStatus($followupMemoTindakLanjut->status) !!}
                            </td>
                            <td class="text-center">
                                {{ $followupMemoTindakLanjut->created_at ? \Base::dateFormat($followupMemoTindakLanjut->created_at) : '-' }}
                            </td>
                            <td class="parent-group text-center">
                                {{ $followupMemoTindakLanjut->updated_at ? \Base::dateFormat($followupMemoTindakLanjut->updated_at) : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="width-40px text-center">{{ $iteration++ }}</td>
                            <td class="parent-group text-left">
                                Memo Tindak Lanjut
                            </td>
                            <td class="parent-group text-center">
                                -
                            </td>
                            <td class="text-center">
                                -
                            </td>
                            <td class="parent-group text-center">
                                -
                            </td>
                        </tr>
                    @endforelse
                    @foreach (\App\Models\Followup\FollowupReschedule::whereRelation('regItem.reg', 'summary_id', $summary->id)->get() as $reschedule)
                        @if (!$reschedule->status)
                            @continue
                        @endif
                        <tr>
                            <td class="width-40px text-center">{{ $iteration++ }}</td>
                            <td class="parent-group text-left">
                                Jadwal Ulang ({{ $reschedule->regItem->sampleDetail->id_temuan }})
                            </td>
                            <td class="parent-group text-center">
                                {!! \Base::getStatus($reschedule->status) !!}
                            </td>
                            <td class="text-center">
                                {{ $reschedule->created_at ? \Base::dateFormat($reschedule->created_at) : '-' }}
                            </td>
                            <td class="parent-group text-center">
                                {{ $reschedule->updated_at ? \Base::dateFormat($reschedule->updated_at) : '-' }}
                            </td>
                        </tr>
                    @endforeach
                    @forelse (\App\Models\Followup\FollowupMonitor::whereRelation('regItem.reg', 'summary_id', $summary->id)->get() as $followupMonitor)
                        <tr>
                            <td class="width-40px text-center">{{ $iteration++ }}</td>
                            <td class="parent-group text-left">
                                Monitoring ({{ $followupMonitor->regItem->sampleDetail->id_temuan }})
                            </td>
                            <td class="parent-group text-center">
                                {!! \Base::getStatus($followupMonitor->status) !!}
                            </td>
                            <td class="text-center">
                                {{ $followupMonitor->created_at ? \Base::dateFormat($followupMonitor->created_at) : '-' }}
                            </td>
                            <td class="parent-group text-center">
                                {{ $followupMonitor->updated_at ? \Base::dateFormat($followupMonitor->updated_at) : '-' }}
                            </td>
                        </tr>
                        @if ($followupReview = $followupMonitor->reviewMonitoring)
                            <tr>
                                <td class="width-40px text-center">{{ $iteration++ }}</td>
                                <td class="parent-group text-left">
                                    Review Monitoring ({{ $followupMonitor->regItem->sampleDetail->id_temuan }})
                                </td>
                                <td class="parent-group text-center">
                                    {!! \Base::getStatus($followupReview->status) !!}
                                </td>
                                <td class="text-center">
                                    {{ $followupReview->created_at ? \Base::dateFormat($followupReview->created_at) : '-' }}
                                </td>
                                <td class="parent-group text-center">
                                    {{ $followupReview->updated_at ? \Base::dateFormat($followupReview->updated_at) : '-' }}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="width-40px text-center">{{ $iteration++ }}</td>
                                <td class="parent-group text-left">
                                    Review Monitoring ({{ $followupMonitor->regItem->sampleDetail->id_temuan }})
                                </td>
                                <td class="parent-group text-center">
                                    -
                                </td>
                                <td class="text-center">
                                    -
                                </td>
                                <td class="parent-group text-center">
                                    -
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td class="width-40px text-center">{{ $iteration++ }}</td>
                            <td class="parent-group text-left">
                                Monitoring
                            </td>
                            <td class="parent-group text-center">
                                -
                            </td>
                            <td class="text-center">
                                -
                            </td>
                            <td class="parent-group text-center">
                                -
                            </td>
                        </tr>
                        <tr>
                            <td class="width-40px text-center">{{ $iteration++ }}</td>
                            <td class="parent-group text-left">
                                Review Monitoring
                            </td>
                            <td class="parent-group text-center">
                                -
                            </td>
                            <td class="text-center">
                                -
                            </td>
                            <td class="parent-group text-center">
                                -
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('buttons')
@endsection

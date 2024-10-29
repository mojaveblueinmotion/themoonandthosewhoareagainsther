@extends('layouts.modal')

@if ($record->status == 'waiting.approval.revisi' || $record->status === 'revisi')
    @section('modal-title', __('Tracking Approval Revisi'))
@elseif ($record->status == 'waiting.approval.upgrade' || $record->status === 'upgrade')
    @section('modal-title', __('Tracking Approval Upgrade'))
@else
    @section('modal-title', __('Tracking Approval'))
@endif

@section('modal-body')
    <div class="timeline timeline-2">
        <div class="timeline-bar"></div>
        @if ($record->loggable_type && $record->loggable_id)
            @forelse($record->log->approval->details as $val)
                <div class="timeline-item d-flex align-items-start">
                    <span class="timeline-badge bg-{{ $val->type === 2 ? 'info' : 'primary' }} mt-5px"></span>
                    <div class="timeline-content d-flex align-items-start justify-content-between">
                        <span class="mr-3">
                            {{ $val->role->name }}
                            <span class="text-{{ $val->type === 2 ? 'info' : 'primary' }}">
                                ({{ $val->type === 2 ? 'Paralel' : 'Sekuensial' }})
                            </span>
                            <div class="text-muted font-italic">
                                <div>{{ __('Approved by:') }} {{ $val->user->name }}</div>
                                <div>{{ __('Approved at:') }}
                                    {{ \Carbon\Carbon::parse($val->approved_at)->creationDate() }}</div>
                            </div>
                        </span>
                        <span class="text-muted font-italic text-right">
                            {!! \App\Support\Base::getStatus($val->status) !!}
                        </span>
                    </div>
                </div>
            @empty
                <div class="alert alert-custom alert-light-danger align-items-center mb-0">
                    <div class="alert-text">{{ __('Data tidak tersedia!') }}</div>
                </div>
            @endforelse
        @else
            @forelse($record->approval->details as $val)
                <div class="timeline-item d-flex align-items-start">
                    <span class="timeline-badge bg-{{ $val->show_color }} mt-5px"></span>
                    <div class="timeline-content d-flex align-items-start justify-content-between">
                        <span class="mr-3">
                            {{ $val->role->name }} <span
                                class="text-{{ $val->show_color }}">({{ $val->show_type }})</span>
                            @if ($val->status === 'approved' && $val->user)
                                <div class="text-muted font-italic">
                                    <div>{{ __('Approved by:') }} {{ $val->user->name }}</div>
                                    <div>{{ __('Approved at:') }} {{ $val->creationDate() }}</div>
                                </div>
                            @else
                                <div class="text-muted font-italic">
                                    <div>{{ __('Created by:') }} {{ $val->creatorName() }}</div>
                                    <div>{{ __('Created at:') }} {{ $val->creationDate() }}</div>
                                </div>
                            @endif
                        </span>
                        <span class="text-muted font-italic text-right">
                            {!! $val->labelStatus() !!}
                        </span>
                    </div>
                </div>
            @empty
                <div class="alert alert-custom alert-light-danger align-items-center mb-0">
                    <div class="alert-text">{{ __('Data tidak tersedia!') }}</div>
                </div>
            @endforelse
        @endif
    </div>
@endsection

@section('buttons')
@endsection

@push('scripts')
    <script>
        $('.modal-dialog').removeClass('modal-lg').addClass('modal-md');
    </script>
@endpush

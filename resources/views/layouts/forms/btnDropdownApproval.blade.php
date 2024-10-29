<div class="btn-group dropdown">
    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        <i class="fa fa-save mr-1"></i> {{ __('Approval') }}
    </button>
    <div class="dropdown-menu dropdown-menu-right">
        <button type="button" class="dropdown-item align-items-center base-form--approveByUrl"
            data-url="{{ $urlApprove ?? (!empty($record) && \Route::has($routes . '.approve') ? rut($routes . '.approve', $record->id) : '') }}">
            <i class="fa fa-check text-primary mr-3"></i> {{ __('Approve') }}
        </button>
        <button type="button" class="dropdown-item" {{ isset($disable_reject) && $disable_reject ? 'disabled' : '' }}
            data-toggle="modal" data-target="#modalReject">
            <i class="fa fa-times text-danger mr-4"></i> {{ __('Reject') }}
        </button>
    </div>
</div>

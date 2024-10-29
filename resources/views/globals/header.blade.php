<div class="row">
    <div class="col-sm-6">
        <div class="form-group row">
            <label class="col-sm-4 col-form-label">{{ __('Tahun') }}</label>
            <div class="col-sm-8">
                <input class="form-control" placeholder="{{ __('Tahun') }}" value="{{ $summary->rkia->year }}" disabled>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label">{{ __('Jenis Audit') }}</label>
            <div class="col-sm-8">
                <input class="form-control" placeholder="{{ __('Tahun') }}" value="{{ $summary->type->name }}" disabled>
            </div>
        </div>
        {{-- <div class="form-group row">
            <label class="col-sm-4 col-form-label">{{ __('Tipe Objek') }}</label>
            <div class="col-sm-8 col-form-label">
                {!! $summary->labelObjectType() !!}
            </div>
        </div> --}}
    </div>
    <div class="col-sm-6">
        <div class="form-group row">
            <label class="col-sm-4 col-form-label">{{ __('Subject Audit') }}</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" value="{{ $summary->subject->name }}" disabled>
            </div>
        </div>
        @if (isset($summary->assignment->status) && $summary->assignment->status === 'completed')
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">{{ __('Surat Penugasan') }}</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="{!! $summary->assignment->getMonthPlan() !!}" disabled>
                </div>
            </div>
        @else
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">{{ __('Surat Penugasan') }}</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="{!! $summary->getLetterNo() . ' (' . $summary->getMonthPlan() . ')' !!}" disabled>
                </div>
            </div>
        @endif
    </div>
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">{{ __('Dept Auditee') }}</label>
            <div class="col-sm-10 parent-group">
                <select class="form-control base-plugin--select2-ajax unitKerja" id="unitKerja"
                    data-placeholder="{{ __('Pilih Salah Satu') }}" disabled multiple>
                        @foreach ($summary->departmentAuditee->departments as $val)
                            <option selected>{{ $val->name }}</option>
                        @endforeach
                </select>
            </div>
        </div>
    </div>
</div>

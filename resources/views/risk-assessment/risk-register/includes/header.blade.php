{{-- {{ dd('blade', $record->getTable(), $record->type->name, $record->subject->name) }} --}}
<div class="row">
    <div class="col-sm-6">
        <div class="form-group row">
            <label class="col-md-4 col-form-label">{{ __('Periode') }}</label>
            <div class="col-md-8 parent-group">
                <input type="text" disabled name="periode" class="form-control base-plugin--datepicker-2 periode"
                    placeholder="{{ __('Periode') }}" value="{{ $record->periode->format('Y') }}">
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group row">
            <label class="col-md-4 col-form-label">{{ __('Jenis Audit') }}</label>
            <div class="col-md-8 parent-group">
                <input type="text" disabled class="form-control" value="{{ $record->type->name }}">
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-md-2 col-form-label">{{ __('Subject Audit') }}</label>
            <div class="col-md-10 parent-group">
                <input type="text" disabled class="form-control" value="{{ $record->subject->name }}">
            </div>
        </div>
    </div>
</div>
<div class="row">

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-md-2 col-form-label">{{ __('Department Auditee') }}</label>
            <div class="col-md-10 parent-group">
                <div>
                    <ol>
                        @foreach ($record->departmentAuditee->departments as $val)
                            <li>{{ $val->name }}</li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

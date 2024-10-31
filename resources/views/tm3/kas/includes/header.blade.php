{{-- {{ dd('blade', $record->getTable(), $record->type->name, $record->subject->name) }} --}}
<div class="row">
    <div class="col-sm-6">
        <div class="form-group row">
            <label class="col-md-4 col-form-label">{{ __('Bulan') }}</label>
            <div class="col-md-8 parent-group">
                <input type="text" disabled name="month" class="form-control base-plugin--datepicker-2 month"
                    placeholder="{{ __('Bulan') }}" value="{{ $record->month->format('F Y') }}">
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group row">
            <label class="col-md-4 col-form-label">{{ __('Lapak') }}</label>
            <div class="col-md-8 parent-group">
                <input type="text" disabled class="form-control" value="{{ $record->lapak->name }}">
            </div>
        </div>
    </div>
    
    <div class="col-sm-6">
        <div class="form-group row">
            <label class="col-md-4 col-form-label">{{ __('Total Debet') }}</label>
            <div class="col-md-8 parent-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text font-weight-bolder">Rp</span>
                    </div>
                    <input type="text" id="get_debet" disabled class="form-control base-plugin--inputmask_currency" value="">
                    
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6">
        <div class="form-group row">
            <label class="col-md-4 col-form-label">{{ __('Total Kredit') }}</label>
            <div class="col-md-8 parent-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text font-weight-bolder">Rp</span>
                    </div>
                    <input type="text" id="get_kredit" disabled class="form-control base-plugin--inputmask_currency" value="">
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-md-2 col-form-label"><b>{{ __('Saldo Akhir') }}</b></label>
            <div class="col-md-10 parent-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text font-weight-bolder">Rp</span>
                    </div>
                    <input type="text" id="get_saldo_sisa" value="" disabled class="form-control base-plugin--inputmask_currency">
                </div>
            </div>
        </div>
    </div>
</div>



@extends('layouts.modal')

@section('action', route($routes . '.detailStore', $record->id))

@section('modal-body')
    @method('POST')
    <div style="max-height:600px; overflow-y:auto; width:100%" class="modal-body">
        <input type="hidden" id="kas_lapak_id" name="kas_lapak_id" value="{{ $record->id ?? 0 }}">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">{{ __('Tanggal') }}</label>
                    <div class="col-md-8 parent-group">
                        <input type="text" name="tgl_input" class="form-control base-plugin--datepicker tgl_input"
                            placeholder="{{ __('Tanggal') }}" value="" data-orientation="bottom" 
                            data-options='@json([
                                'startDate' => '01/' . $record->month->format('m/Y'),
								'endDate' => '01/' . $record->month->addMonth()->format('m/Y'),
                            ])' autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">{{ __('Tipe Mutasi') }}</label>
                    <div class="col-sm-8 parent-group">
                        <select name="tipe" class="form-control base-plugin--select2-ajax tipe" id="tipe"
                            placeholder="{{ __('Pilih Salah Satu') }}">
                            <option value="1" selected>{{ __('Kredit') }}</option>
                            <option value="2">{{ __('Debet') }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">{{ __('Keterangan') }}</label>
                    <div class="col-sm-10 parent-group">
                        <input type="text" name="keterangan" class="form-control" value=""
                            placeholder="{{ __('Keterangan') }}">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">{{ __('Total Mutasi') }}</label>
                    <div class="col-md-8 parent-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text font-weight-bolder">Rp</span>
                            </div>
                            <input value="0" class="form-control base-plugin--inputmask_currency total" id="total" name="total"
                                placeholder="{{ __('Total Mutasi') }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">{{ __('Saldo Akhir') }}</label>
                    <div class="col-md-8 parent-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text font-weight-bolder">Rp</span>
                            </div>
                            <input class="form-control base-plugin--inputmask_currency saldo_sisa" id="saldo_sisa" name="saldo_sisa"
                                readonly
                                placeholder="{{ __('Saldo Akhir') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="sisaSaldoDb" value="{{ App\Models\Tm1\KasLapakDetail::where('kas_lapak_id', $record->id)->count() ? App\Models\Tm1\KasLapakDetail::where('kas_lapak_id', $record->id)->orderBy('id', 'desc')->first()->saldo_sisa : 0 }}">
        <input type="hidden" value="{{ App\Models\Tm1\KasLapakDetail::where('kas_lapak_id', $record->id)->count() }}" id="countDetail">
        <hr>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">{{ __('Keterangan Tambahan') }}</label>
                    <div class="col-sm-10 parent-group">
                        <input type="text" name="description" class="form-control" value=""
                            placeholder="{{ __('Keterangan Tambahan') }}">
                    </div>
                </div>
            </div>
        </div>
    </div

@endsection

@push('scripts')
@include($views.'.includes.scripts')
@endpush

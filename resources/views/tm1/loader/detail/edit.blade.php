@extends('layouts.modal')

@section('action', route($routes . '.detailUpdate', $detail->id))

@section('modal-body')
    @method('PATCH')
    <div style="max-height:600px; overflow-y:auto; width:100%" class="modal-body">
        <input type="hidden" id="loader_id" name="loader_id" value="{{ $detail->loader_id ?? 0 }}">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">{{ __('Tanggal') }}</label>
                    <div class="col-md-8 parent-group">
                        <input value="{{ $detail->tgl_input->format('d/m/Y') }}" type="text" name="tgl_input" class="form-control base-plugin--datepicker tgl_input"
                            placeholder="{{ __('Tanggal') }}" value="" data-orientation="bottom" 
                            data-options='@json([
                                'startDate' => '01/' . $detail->loader->month->format('m/Y'),
                                'endDate' => '',
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
                            <option @if($detail->tipe == 1) selected @endif value="1" selected>{{ __('Kredit') }}</option>
                            <option @if($detail->tipe == 2) selected @endif value="2">{{ __('Debet') }}</option>
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
                        <input value="{{ $detail->keterangan }}" type="text" name="keterangan" class="form-control" value=""
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
                            <input value="{{ $detail->total }}" class="form-control base-plugin--inputmask_currency total" id="total" name="total"
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
                            <input class="form-control base-plugin--inputmask_currency saldo_sisa" id="saldo_sisa" name="saldo_sisa" value="{{ $saldoHistory[$detail->id] }}" readonly
                                placeholder="{{ __('Saldo Akhir') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @php
            $getLastId = App\Models\Tm1\LoaderDetail::where('loader_id', $detail->loader->id)->where('id', '<', $detail->id)->orderBy('id', 'desc')->first()->id ?? null;
        @endphp

        @if($getLastId == null)
        <input type="hidden" id="sisaSaldoDb" value="0">
        @else
        <input type="hidden" id="sisaSaldoDb" value="{{ $saldoHistory[$getLastId] }}">
        @endif
        <input type="hidden" value="{{ App\Models\Tm1\LoaderDetail::where('loader_id', $detail->loader->id)->count() }}" id="countDetail">


        
        <input type="hidden" id="sisaSaldoDb" value="{{ $saldoHistory[$detail->id] }}">
        <input type="hidden" value="{{ App\Models\Tm1\LoaderDetail::where('loader_id', $detail->loader->id)->count() }}" id="countDetail">

        <div class="row">
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">{{ __('Keterangan Tambahan') }}</label>
                    <div class="col-sm-10 parent-group">
                        <input type="text" value="{{ $detail->description }}" name="description" class="form-control" value=""
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
@extends('layouts.modal')

@section('action', route($routes . '.detailStore', $record->id))

@section('modal-body')
    @method('POST')
    <div style="max-height:600px; overflow-y:auto; width:100%" class="modal-body">
        <input type="hidden" id="pembukuan_lapak_id" name="pembukuan_lapak_id" value="{{ $record->id ?? 0 }}">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">{{ __('No Timbangan') }}</label>
                    <div class="col-sm-10 parent-group">
                        <input type="text" name="no_timbangan" class="form-control" value=""
                            placeholder="{{ __('No Timbangan') }}">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">{{ __('Vendor') }}</label>
                    <div class="col-sm-8 parent-group">
                        <input type="text" name="vendor" class="form-control" value=""
                            placeholder="{{ __('Vendor') }}">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">{{ __('Tgl Masuk') }}</label>
                    <div class="col-md-8 parent-group">
                        <input type="text" name="tgl_masuk" class="form-control base-plugin--datepicker tgl_masuk"
                            placeholder="{{ __('Tgl Masuk') }}" value="" data-orientation="bottom" 
                            data-options='@json([
                                'startDate' => '01/' . $record->month->format('m/Y'),
								'endDate' => '01/' . $record->month->addMonth()->format('m/Y'),
                            ])' autocomplete="off">
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">{{ __('Gross') }}</label>
                    <div class="col-md-8 parent-group">
                        <div class="input-group">
                            <input class="form-control base-plugin--inputmask_currency gross" id="gross" name="gross"
                                placeholder="{{ __('Gross') }}">
                            <div class="input-group-prepend">
                                <span class="input-group-text font-weight-bolder">Kg</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">{{ __('Tare') }}</label>
                    <div class="col-md-8 parent-group">
                        <div class="input-group">
                            <input class="form-control base-plugin--inputmask_currency tere" id="tere" name="tere"
                                placeholder="{{ __('Tare') }}">
                            <div class="input-group-prepend">
                                <span class="input-group-text font-weight-bolder">Kg</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">{{ __('Bruto') }}</label>
                    <div class="col-md-8 parent-group">
                        <div class="input-group">
                            <input class="form-control base-plugin--inputmask_currency bruto" id="bruto" name="bruto"
                                placeholder="{{ __('Bruto') }}">
                            <div class="input-group-prepend">
                                <span class="input-group-text font-weight-bolder">Kg</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">{{ __('Refaksi') }}</label>
                    <div class="col-md-8 parent-group">
                        <div class="input-group">
                            <input class="form-control base-plugin--inputmask_currency masking-code refaksi" id="refaksi" name="refaksi"
                                placeholder="{{ __('Refaksi') }}">
                            <div class="input-group-prepend">
                                <span class="input-group-text font-weight-bolder">%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">{{ __('Potongan') }}</label>
                    <div class="col-md-8 parent-group">
                        <div class="input-group">
                            <input class="form-control base-plugin--inputmask_currency potongan" id="potongan" name="potongan"
                                placeholder="{{ __('Potongan') }}">
                            <div class="input-group-prepend">
                                <span class="input-group-text font-weight-bolder">Kg</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">{{ __('Netto') }}</label>
                    <div class="col-md-8 parent-group">
                        <div class="input-group">
                            <input class="form-control base-plugin--inputmask_currency netto" id="netto" name="netto"
                                placeholder="{{ __('Netto') }}">
                            <div class="input-group-prepend">
                                <span class="input-group-text font-weight-bolder">Kg</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">{{ __('Harga') }}</label>
                    <div class="col-md-8 parent-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text font-weight-bolder">Rp</span>
                            </div>
                            <input value="0" class="form-control base-plugin--inputmask_currency harga" id="harga" name="harga"
                                placeholder="{{ __('Harga') }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">{{ __('Jumlah') }}</label>
                    <div class="col-md-8 parent-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text font-weight-bolder">Rp</span>
                            </div>
                            <input value="0" class="form-control base-plugin--inputmask_currency jumlah" id="jumlah" name="jumlah"
                                placeholder="{{ __('Jumlah') }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">{{ __('Biaya Bongkar & Ampera') }}</label>
                    <div class="col-md-8 parent-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text font-weight-bolder">Rp</span>
                            </div>
                            <input value="0" class="form-control base-plugin--inputmask_currency biaya_bongkar_ampera" id="biaya_bongkar_ampera" name="biaya_bongkar_ampera"
                                placeholder="{{ __('Biaya Bongkar & Ampera') }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">{{ __('Premi Supir') }}</label>
                    <div class="col-md-8 parent-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text font-weight-bolder">Rp</span>
                            </div>
                            <input value="0" class="form-control base-plugin--inputmask_currency premi_supir" id="premi_supir" name="premi_supir"
                                placeholder="{{ __('Premi Supir') }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">{{ __('Premi Agen') }}</label>
                    <div class="col-md-8 parent-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text font-weight-bolder">Rp</span>
                            </div>
                            <input value="0" class="form-control base-plugin--inputmask_currency premi_agen" id="premi_agen" name="premi_agen"
                                placeholder="{{ __('Premi Agen') }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">{{ __('Bongkaran') }}</label>
                    <div class="col-md-8 parent-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text font-weight-bolder">Rp</span>
                            </div>
                            <input value="0" class="form-control base-plugin--inputmask_currency bongkaran" id="bongkaran" name="bongkaran"
                                placeholder="{{ __('Bongkaran') }}">
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            
            <div class="col-sm-12">
                <hr>
                <div class="form-group row">
                    <label class="col-sm-12 col-form-label">{{ __('Pembayaran Lainnya') }}</label>
                    <div class="col-sm-12 parent-group">
                        <div class="table-responsive">
                            <table class="table-bordered table">
                                <thead>
                                    <tr>
                                        <th class="width-40px text-center">#</th>
                                        <th class="text-center">{{ __('Nama') }}</th>
                                        <th class="text-center">{{ __('Total') }}</th>
                                        {{-- <th class="valign-top width-30px text-center">
                                            <button type="button"
                                                class="btn btn-sm btn-icon btn-circle btn-primary add-ext-part"><i
                                                    class="fa fa-plus"></i></button>
                                        </th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr data-key="1">
                                        <td class="width-40px no text-center">1</td>
                                        <td class="parent-group text-left">
                                            <select name="parts[1][pembayaran_id]" class="form-control base-plugin--select2-ajax"
                                                data-url="{{ rut('ajax.selectPembayaran', ['search' => 'all']) }}"
                                                data-url-origin="{{ rut('ajax.selectPembayaran', ['search' => 'all']) }}"
                                                placeholder="{{ __('Pilih Salah Satu ') }}">
                                                <option value="">{{ __('Pembayaran') }}</option>
                                            </select>
                                        </td>
                                        <td class="parent-group text-left">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text font-weight-bolder">Rp</span>
                                                </div>
                                                <input class="form-control base-plugin--inputmask_currency totalDetail" name="parts[1][total]"
                                                    placeholder="{{ __('Total') }}">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr data-key="2">
                                        <td class="width-40px no text-center">2</td>
                                        <td class="parent-group text-left">
                                            <select name="parts[2][pembayaran_id]" class="form-control base-plugin--select2-ajax"
                                                data-url="{{ rut('ajax.selectPembayaran', ['search' => 'all']) }}"
                                                data-url-origin="{{ rut('ajax.selectPembayaran', ['search' => 'all']) }}"
                                                placeholder="{{ __('Pilih Salah Satu ') }}">
                                                <option value="">{{ __('Pembayaran') }}</option>
                                            </select>
                                        </td>
                                        <td class="parent-group text-left">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text font-weight-bolder">Rp</span>
                                                </div>
                                                <input class="form-control base-plugin--inputmask_currency totalDetail" name="parts[2][total]"
                                                    placeholder="{{ __('Total') }}">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr data-key="3">
                                        <td class="width-40px no text-center">3</td>
                                        <td class="parent-group text-left">
                                            <select name="parts[3][pembayaran_id]" class="form-control base-plugin--select2-ajax"
                                                data-url="{{ rut('ajax.selectPembayaran', ['search' => 'all']) }}"
                                                data-url-origin="{{ rut('ajax.selectPembayaran', ['search' => 'all']) }}"
                                                placeholder="{{ __('Pilih Salah Satu ') }}">
                                                <option value="">{{ __('Pembayaran') }}</option>
                                            </select>
                                        </td>
                                        <td class="parent-group text-left">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text font-weight-bolder">Rp</span>
                                                </div>
                                                <input class="form-control base-plugin--inputmask_currency totalDetail" name="parts[3][total]"
                                                    placeholder="{{ __('Total') }}">
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-md-2 col-form-label">{{ __('Total Dibayar') }}</label>
                    <div class="col-md-10 parent-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text font-weight-bolder">Rp</span>
                            </div>
                            <input value="0" class="form-control base-plugin--inputmask_currency total_dibayar" id="total_dibayar" name="total_dibayar"
                                placeholder="{{ __('Total Dibayar') }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-md-2 col-form-label">{{ __('Pengeluaran Lapak') }}</label>
                    <div class="col-md-10 parent-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text font-weight-bolder">Rp</span>
                            </div>
                            <input value="0" class="form-control base-plugin--inputmask_currency pengeluaran_lapak" id="pengeluaran_lapak" name="pengeluaran_lapak"
                                placeholder="{{ __('Pengeluaran Lapak') }}">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-md-2 col-form-label">{{ __('Kirim Pabrik') }}</label>
                    <div class="col-md-10 parent-group">
                        <input type="text" name="kirim_pabrik" class="form-control base-plugin--datepicker kirim_pabrik"
                            placeholder="{{ __('Kirim Pabrik') }}" value="" data-orientation="bottom" 
                            data-options='@json([
                                'startDate' => '',
                                'endDate' => '',
                            ])' autocomplete="off">
                    </div>
                </div>
            </div>
        </div>
    </div

@endsection

@push('scripts')
@include($views.'.includes.scripts')
@endpush

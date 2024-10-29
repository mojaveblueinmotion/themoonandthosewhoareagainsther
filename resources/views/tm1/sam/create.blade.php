@extends('layouts.modal')

@section('action', rut($routes . '.store'))

@section('modal-body')
    @method('POST')
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Lapak') }}</label>
        <div class="col-sm-8 parent-group">
            <input type="hidden" name="perusahaan_id" value="{{ App\Models\Master\Pembukuan\Lapak::where('id', 1)->first()->id }}">
            <select disabled name="perusahaan_id" data-post="perusahaan_id" class="form-control base-plugin--select2-ajax perusahaan_id"
                data-url="{{ rut('ajax.selectLapak', ['search' => 'all']) }}"
                data-url-origin="{{ rut('ajax.selectLapak', ['search' => 'all']) }}"
                placeholder="{{ __('Pilih Salah Satu ') }}">
                <option value="">{{ __('Lapak ') }}</option>
                <option value="1" selected>{{ App\Models\Master\Pembukuan\Lapak::where('id',1)->first()->name }}</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Bulan') }}</label>
        <div class="col-sm-8 parent-group">
            <input type="text" name="month" class="form-control base-plugin--datepicker-2 month" id="tahun"
                placeholder="{{ __('Bulan') }}">
        </div>
    </div>

@endsection

@push('scripts')
    <script>
         $(function() {
            $('.modal-dialog-right-bottom').removeClass('modal-md').addClass('modal-lg');
            $(".masking-mandays").inputmask({
                "mask": "9",
                "repeat": 16,
                "greedy": false
            });
        });
    </script>
@endpush

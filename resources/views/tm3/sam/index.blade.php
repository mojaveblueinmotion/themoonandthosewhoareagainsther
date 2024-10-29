@extends('layouts.lists')

@section('filters')
    <div class="row">
        <div class="col-12 col-sm-3 col-xl-1 mr-n6 pb-2">
            <input class="form-control base-plugin--datepicker-2 filter-control" data-post="month"
                placeholder="{{ __('Bulan') }}">
        </div>
        <div class="col-12 col-sm-6 col-md-3 mr-n6 pb-2">
            <select disabled name="perusahaan_id" data-post="perusahaan_id" class="form-control filter-control base-plugin--select2-ajax filter-perusahaan_id"
                data-url="{{ rut('ajax.selectLapak', ['search' => 'all']) }}"
                data-url-origin="{{ rut('ajax.selectLapak', ['search' => 'all']) }}"
                placeholder="{{ __('Lapak ') }}">
                <option value="3" selected>{{ App\Models\Master\Pembukuan\Lapak::where('id',3)->first()->name }}</option>
            </select>
        </div>
        <div class="col-12 col-sm-5 col-md-2 pb-2">
            <select class="form-control filter-control base-plugin--select2 filter-status" data-post="status"
                data-placeholder="{{ __('Status') }}">
                <option value="*">Semua</option>
                <option value="new">New</option>
                <option value="draft">Draft</option>
                <option value="completed">Completed</option>
            </select>
        </div>
    </div>
@endsection

@section('buttons')
    @if (auth()->user()->checkPerms($perms . '.create'))
        @include('layouts.forms.btnAdd')
    @endif
@endsection

@push('scripts')
    <script>
        $(function() {
            $('.content-page')
                .on('click', '.filter.button, .reset.button', function() {
                    setTimeout(() => {
                        $('.filter-status').select2('destroy').val('*').change();
                        $('.filter-perusahaan_id').val('1').prop('disabled', true);;
                        BasePlugin.initSelect2();
                    }, 200);
                });
        });
    </script>
@endpush

@extends('layouts.lists')

@section('filters')
    <div class="row">
        <div class="ml-6 pb-2" style="width: 300px">
            <select class="form-control base-plugin--select2-ajax filter-control" data-post="module_name"
                data-placeholder="{{ __('Modul') }}">
                <option value="" selected>{{ __('Modul') }}</option>
                @foreach (\Base::getModulesMain() as $key => $val)
                    @if (in_array($key, ['dashboard', 'monitoring', 'monitoring-temuan']))
                        @continue
                    @endif
                    @if (in_array($key, ['setting.profile', 'auth_login', 'auth_logout']))
                        @continue
                    @endif
                    <option value="{{ $key }}">{{ $val }}</option>
                @endforeach
                <option value="auth_">Autentikasi</option>
            </select>
        </div>
        <div class="ml-4 pb-2" style="width: 250px">
            <div class="input-group">
                <input type="text" data-post="date_start"
                    class="form-control filter-control base-plugin--datepicker date-start"
                    placeholder="{{ __('Mulai') }}" data-options='@json([
                        'startDate' => '',
                        'endDate' => now()->format(' d/m/Y'),
                    ])'>
                <div class="input-group-append">
                    <span class="input-group-text">
                        <i class="la la-ellipsis-h"></i>
                    </span>
                </div>
                <input type="text" data-post="date_end"
                    class="form-control filter-control base-plugin--datepicker date-end" placeholder="{{ __('Selesai') }}"
                    data-options='@json([
                        'startDate' => '',
                        'endDate' => now()->format(' d/m/Y'),
                    ])' disabled>
            </div>
        </div>
    </div>
@endsection

@section('buttons')
<a href="{{ rut($routes . '.runScheduler') }}" target="_blank" class="btn btn-info base-form--postByUrl text-nowrap ml-2" 
    data-swal-ok="Jalankan" data-swal-text="Apakah anda yakin ingin menjalankan scheduler?">
    <i class="far fa-clock mr-2"></i> Run Scheduler
</a>
@endsection

@push('scripts')
    <script>
        $(function() {

            var toTime = function(date) {
                var ds = date.split('/');
                var year = ds[2];
                var month = ds[1];
                var day = ds[0];
                return new Date(year + '-' + month + '-' + day).getTime();
            }

            $('.content-page').on('changeDate', 'input.date-start', function(value) {
                var me = $(this),
                    startDate = new Date(value.date.valueOf()),
                    date_end = me.closest('.input-group').find('input.date-end');

                if (me.val()) {
                    if (toTime(me.val()) > toTime(date_end.val())) {
                        date_end.datepicker('update', '')
                            .datepicker('setStartDate', startDate)
                            .prop('disabled', false);
                    } else {
                        date_end.datepicker('update', date_end.val())
                            .datepicker('setStartDate', startDate)
                            .prop('disabled', false);
                    }
                } else {
                    date_end.datepicker('update', '')
                        .prop('disabled', true);
                }
            });
        });
    </script>
@endpush

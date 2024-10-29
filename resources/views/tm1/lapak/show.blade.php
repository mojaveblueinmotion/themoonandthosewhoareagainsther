@extends('layouts.page')

@section('content-body')
    <div class="flex-column-fluid">
        <form action="{{ route($routes . '.submitSave', $record->id) }}" method="POST" autocomplete="off">
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            <div class="container-fluid">
                <div class="card card-custom">
                    <div class="card-header">
                        <h3 class="card-title">Pembukuan Lapak</h3>
                        <div class="card-toolbar">
                        @section('card-toolbar')
                            @include('layouts.forms.btnBackTop')
                        @show
                    </div>
                </div>
                <div class="card-body">
                    @include($views . '.includes.header')
                </div>
            </div>
        </div>
        <div class="container-fluid mt-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-custom">
                        <div class="card-header">
                            <h3 class="card-title">Detail Pembukuan Lapak</h3>
                            <div class="card-toolbar">
                            </div>
                        </div>
                        <div class="card-body">
                            @include($views . '.detail.detail-grid-laporan')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('buttons')
@endsection

@push('scripts')
    <script>
		getTotalPembukuan();

        function getTotalPembukuan() {
            let pembukuan_lapak_id = {{ $record->id }};
            console.log(pembukuan_lapak_id);
            $.ajax({
                method: 'GET',
                url: '{{ yurl('/ajax/getTotalPembukuan') }}',
                data: {
                    pembukuan_lapak_id: pembukuan_lapak_id,
                },
                success: function(response, state, xhr) {
                    if (response) {
                        $('#get_total_dibayar').val(response.total_dibayar);
                        $('#get_pengeluaran_lapak').val(response.pengeluaran_lapak);
                        $('#get_gross').val(response.gross);
                        $('#get_netto').val(response.netto);
                    } else {
                        $('#get_total_dibayar').val(null);
                        $('#get_pengeluaran_lapak').val(null);
                        $('#get_gross').val(null);
                        $('#get_netto').val(null);
                    }
                },
                error: function(a, b, c) {
                    console.log(a, b, c);
                }
            });
        }

        BaseList.draw(['#datatable_1'], {
            callback: function(a, b) {
                getTotalPembukuan();
            }
        });
    </script>
@endpush
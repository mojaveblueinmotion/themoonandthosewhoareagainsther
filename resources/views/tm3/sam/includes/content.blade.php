@extends('layouts.page')

@section('content-body')
    <div class="flex-column-fluid">
        <form action="{{ route($routes . '.submitSave', $record->id) }}" method="POST" autocomplete="off">
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            <div class="container-fluid">
                <div class="card card-custom">
                    <div class="card-header">
                        <h3 class="card-title">Pembukuan Sam</h3>
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
                            <h3 class="card-title">Detail Pembukuan Sam</h3>
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
        @if (request()->route()->getName() !=
                $routes . '.detail.show')
            <div class="container-fluid mt-5">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-custom" style="height:100%;">
                            <div class="card-header">
                                <h3 class="card-title">Aksi</h3>
                                <div class="card-toolbar">
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-end">
                                    @include('layouts.forms.btnDropdownSubmit')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
</div>
</div>
@endsection
@section('buttons')
@endsection

@push('scripts')
    <script>
		getTotalPembukuanSam();

        function getTotalPembukuanSam() {
            let pembukuan_sam_id = {{ $record->id }};
            $.ajax({
                method: 'GET',
                url: '{{ yurl('/ajax/getTotalPembukuanSam') }}',
                data: {
                    pembukuan_sam_id: pembukuan_sam_id,
                },
                success: function(response, state, xhr) {
                    if (response) {
                        $('#get_total_dibayar').val(response.total_dibayar);
                        $('#get_hasil_akhir').val(response.hasil_akhir);
                        $('#get_gross').val(response.gross);
                        $('#get_netto').val(response.netto);
                    } else {
                        $('#get_total_dibayar').val(null);
                        $('#get_hasil_akhir').val(null);
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
                getTotalPembukuanSam();
            }
        });
    </script>
@endpush
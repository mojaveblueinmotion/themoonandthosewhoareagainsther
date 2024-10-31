@extends('layouts.page')

@section('content-body')
    <div class="flex-column-fluid">
        <form action="{{ route($routes . '.submitSave', $record->id) }}" method="POST" autocomplete="off">
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            <div class="container-fluid">
                <div class="card card-custom">
                    <div class="card-header">
                        <h3 class="card-title">Loader</h3>
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
                            <h3 class="card-title">Detail Loader</h3>
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
		getTotalLoader();

        function getTotalLoader() {
            let loader_id = {{ $record->id }};
            $.ajax({
                method: 'GET',
                url: '{{ yurl('/ajax/getTotalLoader') }}',
                data: {
                    loader_id: loader_id,
                },
                success: function(response, state, xhr) {
                    if (response) {
                        $('#get_debet').val(response.debet);
                        $('#get_kredit').val(response.kredit);
                        $('#get_saldo_sisa').val(response.saldo_sisa);
                    } else {
                        $('#get_debet').val(null);
                        $('#get_kredit').val(null);
                        $('#get_saldo_sisa').val(null);
                    }
                },
                error: function(a, b, c) {
                    console.log(a, b, c);
                }
            });
        }

        BaseList.draw(['#datatable_1'], {
            callback: function(a, b) {
                getTotalLoader();
            }
        });
    </script>
@endpush
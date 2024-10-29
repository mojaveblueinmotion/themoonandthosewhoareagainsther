@extends('layouts.page')

@section('content-body')
    <div class="flex-column-fluid">
        <form action="{{ route($routes . '.submitSave', $record->id) }}" method="POST" autocomplete="off">
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            <div class="container-fluid">
                <div class="card card-custom">
                    <div class="card-header">
                        <h3 class="card-title">Risk Register</h3>
                        <div class="card-toolbar">
                        @section('card-toolbar')
                            @include('layouts.forms.btnBackTop')
                        @show
                    </div>
                </div>
                <div class="card-body">
                    @include($views . '.includes.notes')
                    @include($views . '.includes.header')
                </div>
            </div>
        </div>
        <div class="container-fluid mt-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-custom">
                        <div class="card-header">
                            <h3 class="card-title">Detail Risk Register</h3>
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

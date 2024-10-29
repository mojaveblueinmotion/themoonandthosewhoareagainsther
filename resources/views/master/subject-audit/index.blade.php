@extends('layouts.lists')

@section('filters')
    <div class="row">
        <div class="ml-4 pb-2" style="width: 350px">
            <input type="text" class="form-control filter-control" data-post="name" placeholder="{{ __('Nama') }}">
        </div>
        <div class="col-12 col-sm-6 col-md-3 mr-n6 pb-2">
            <select name="type_id" data-post="type_id"
                class="form-control filter-control base-plugin--select2-ajax filter-type-id"
                data-url="{{ rut('ajax.selectTypeAudit', ['search' => 'all']) }}" placeholder="{{ __('Jenis Audit') }}">
                <option value="">{{ __('Jenis Audit') }}</option>
            </select>
        </div>
    </div>
@endsection

@section('buttons')
    @if (auth()->user()->checkPerms($perms . '.create'))
        @include('layouts.forms.btnAdd')
    @endif
@endsection

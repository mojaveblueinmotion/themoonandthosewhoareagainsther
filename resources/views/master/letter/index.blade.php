@extends('layouts.lists')

@inject('menu', 'App\Models\Globals\Menu')

@section('filters')
    <div class="row">
        <div class="ml-4 pb-2" style="width: 250px">
            <select data-post="type" class="form-control filter-control base-plugin--select2"
                data-placeholder="{{ __('Modul') }}">
                <option value="" selected>{{ __('Modul') }}</option>
                @foreach ($menu->grid()->get() as $menu)
                @if ($menu->parent_id == NULL)
                <option value="{{ $menu->module }}">{{ $menu->show_module }}</option>
                @endif
                @endforeach
            </select>
        </div>
    </div>
@endsection

@section('buttons')
@endsection

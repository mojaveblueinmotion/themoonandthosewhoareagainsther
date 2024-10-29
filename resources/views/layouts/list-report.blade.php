@extends('layouts.app')

@section('title', __($title))

@section('content')
@section('content-header')
    @include('layouts.base.subheader')
@show
@section('content-body')
    <div class="d-flex flex-column-fluid">
        <div class="{{ empty($container) ? 'container-fluid' : $container }}">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-custom">
	                    <div class="card-body">
                            @yield('start-list')
                        </div>
                    </div>
                </div>
            </div>
            @yield('end-list')
        </div>
    </div>
@show
@endsection

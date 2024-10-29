@extends('layouts.app')

@section('content')
@section('content-header')
    @include('layouts.base.subheadernobuttons')
@show
@php
$colors = [
1 => 'primary',
2 => 'info',
];
@endphp
<div class="{{ $container ?? 'container-fluid' }}">
	<form action="@yield('action', '#')" method="POST" autocomplete="@yield('autocomplete', 'off')">
		<div class="card card-custom mb-4">
			@section('card-header')
		    	<div class="card-header">
		    		<h3 class="card-title">@yield('card-title', $title)</h3>
					<div class="card-toolbar">
						@section('card-toolbar')
							@include('layouts.forms.btnBackTop')
						@show
					</div>
		        </div>
			@show

			<div class="card-body">
				@csrf
				@yield('card-body')
			</div>
			
			@section('buttons')
				<div class="card-footer">
					@section('card-footer')
						<div class="d-flex justify-content-between">
							@include('layouts.forms.btnBack')
							@include('layouts.forms.btnSubmitPage')
						</div>
					@show
				</div>
			@show
		</div>
	    @yield('card-footer')
	</form>
</div>
@endsection
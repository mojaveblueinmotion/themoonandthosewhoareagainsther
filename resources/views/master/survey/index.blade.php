@extends('layouts.lists')

@section('filters')
	<div class="row">
		<div class="ml-4 pb-2" style="width: 350px">
			<input type="text" class="form-control filter-control" data-post="description"
				placeholder="{{ __('Deskripsi') }}">
		</div>
	</div>
@endsection

@section('buttons')
	@if (auth()->user()->checkPerms($perms.'.create'))
		@include('layouts.forms.btnAdd')
	@endif
@endsection

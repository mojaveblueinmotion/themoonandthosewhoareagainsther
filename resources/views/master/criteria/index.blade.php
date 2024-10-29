@extends('layouts.lists')

@section('filters')
    <div class="row">
        <div class="ml-6 pb-2 mr-n2" style="width: 250px">
            <input type="text" data-post="name" class="form-control filter-control" placeholder="{{ __('Nama') }}">
        </div>
    </div>
@endsection

@section('buttons')
	@if (auth()->user()->checkPerms($perms.'.create'))
		@include('layouts.forms.btnAdd')
	@endif
@endsection

@push('scripts')
    <script>
        $(function() {
            window.formSuccessCallback = function(resp, form, options) {
                $('#nameCtrl').val('');
                $('#descCtrl').val('');
            };
        });
    </script>
@endpush

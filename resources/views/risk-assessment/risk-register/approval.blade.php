@extends('layouts.page', ['container' => 'container'])

@section('content-body')
@method('POST')
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
            @include($views.'.includes.header')
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
					@include($views.'.detail.detail-grid-laporan')
				</div>
			</div>
            @if ($record->checkAction('approval', $perms))
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    @include('layouts.forms.btnBack')
                    @include('layouts.forms.btnDropdownApproval')
                </div>
            </div>
            @include('layouts.forms.modalReject')
            @endif
		</div>
	</div>
</div>
@endsection

@section('buttons')
@endsection

@push('scripts')
@endpush
@section('card-footer')
@endsection
@section('page-end')

@endsection
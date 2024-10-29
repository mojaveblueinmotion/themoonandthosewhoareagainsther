{{-- {{ dd(session('remember_username')) }} --}}
@extends('layouts.page')

@section('page')
{{-- @if ($user->hasRole('Administrator') || $user->hasRole('Super Administrator'))
<div aria-labelledby="admin-tab" class="tab-pane fade show active" id="admin" role="tabpanel">
    <div class="row">
        @include($views . '.admin._chart-login')
        @include($views . '.admin._chart-login-monthly')
    </div>
</div>
@else
    <div class="row">
        @include($views . '._card-progress')
    </div>
    <div class="row">
        @include($views . '._chart-finding')
        @include($views . '._chart-followup')
    </div>
    @endif --}}

@endsection

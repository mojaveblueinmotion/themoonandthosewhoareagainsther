<button type="button" class="btn btn-secondary btn-back base-content--replace"
	data-url="{{ $urlBack ?? (\Route::has($routes.'.index') ? rut($routes.'.index') : yurl()->previous()) }}"
    tabindex="-1">
	<i class="fa fa-chevron-left mr-1"></i>
	{{ __('Kembali') }}
</button>

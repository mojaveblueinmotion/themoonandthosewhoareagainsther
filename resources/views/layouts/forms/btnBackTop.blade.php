<a href="{{ $urlBack ?? (\Route::has($routes.'.index') ? rut($routes.'.index') : yurl()->previous()) }}"
	class="btn btn-hover-text-primary font-weight-bolder pr-0 base-content--replace"
	data-toggle="tooltip"
	data-original-title="{{ __('Kembali') }}"
	data-placement="top">
	<i aria-hidden="true" class="ki ki-close"></i>
</a>
@extends('layouts.lists')

@section('filters')
    <div class="row">
        <div class="ml-4 pb-2" style="width: 350px">
            <input type="text" class="form-control filter-control" data-post="name" placeholder="{{ __('Nama') }}">
        </div>
    </div>
@endsection

@section('buttons')
    @if (auth()->user()->checkPerms($perms . '.create'))
        @include('layouts.forms.btnAdd')
    @endif
@endsection

@push('scripts')
    <script>
        $(function() {
            window.formSuccessCallback = function(resp, form, options) {
                $('#nameCtrl').val('');
                $('#desc').val('');
            };
        })
        $('.content-page').on('change', 'select.filter-type-id', function(e) {
                var me = $(this);
                var objectId = $('select.filter-object');

                if (me.val()) {
                    var urlOrigin = objectId.data('url-origin');
                    var urlParam = $.param({
                        category: 'by_type',
                        type_id: me.val()
                    });
                    objectId.data('url', decodeURIComponent(decodeURIComponent(urlOrigin + '?' + urlParam)));
                    objectId.val(null).prop('disabled', false);
                } else {
                    objectId.prop('disabled', true).val(null); // Disable and reset value
                }

                BasePlugin.initSelect2();
            });
            $('.content-page').on('click', '.reset-filter .reset.button', function(e) {
                var me = $(this);
                var objectId = $('select.filter-object');
                var urlOrigin = objectId.data('url-origin');
                var urlParam = $.param({
                    category: ''
                });
                objectId.data('url', decodeURIComponent(decodeURIComponent(urlOrigin + '?' + urlParam)));
                objectId.val(null).prop('disabled', true);
                BasePlugin.initSelect2();
            });
    </script>
@endpush

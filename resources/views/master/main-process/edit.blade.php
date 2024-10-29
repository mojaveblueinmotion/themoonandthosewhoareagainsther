@extends('layouts.modal')

@section('action', rut($routes.'.update', $record->id))

@section('modal-body')
	@method('PATCH')
	<div class="form-group row">
        <label class="col-sm-3 col-form-label">{{ __('Jenis Audit') }}</label>
        <div class="col-sm-9 parent-group">
            <select disabled data-post="type_id" name="type_id" class="form-control filter-control base-plugin--select2-ajax type_id"
                data-url="{{ rut('ajax.selectTypeAudit', ['search' => 'all']) }}"
                data-url-origin="{{ rut('ajax.selectTypeAudit', ['search' => 'all']) }}"
                placeholder="{{ __('Pilih Salah Satu ') }}">
                <option value="">{{ __('Pilih Salah Satu') }}</option>
                <option selected value="{{ $record->subject->typeAudit->id }}">{{ $record->subject->typeAudit->name }}</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">{{ __('Subject Audit') }}</label>
        <div class="col-sm-9 parent-group">
            <input type="text" name="subject_id" value="{{ $record->subject_id }}" hidden>
            <select name="subject_id" id="subjectAudit" class="form-control base-plugin--select2-ajax subjectAudit"
                data-url="{{ rut('ajax.selectStruct', ['search' => 'subject', 'type_id' => '']) }}"
                data-url-origin="{{ rut('ajax.selectStruct', 'subject') }}" disabled
                placeholder="{{ __('Pilih Salah Satu') }}">
                <option selected value="{{ $record->subject_id }}">{{ $record->subject->name }}</option>
            </select>
        </div>
    </div>
	<div class="form-group row">
		<label class="col-3 col-form-label">{{ __('Nama') }}</label>
		<div class="col-9 parent-group">
			<input type="text" name="name" class="form-control" placeholder="{{ __('Nama') }}" value="{{ $record->name }}">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-3 col-form-label">{{ __('Deskripsi') }}</label>
		<div class="col-9 parent-group">
			<textarea name="description" class="form-control" placeholder="{{ __('Deskripsi') }}">{{ $record->description }}</textarea>
		</div>
	</div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('.content-page').on('change', 'select.type_id', function(e) {
                var me = $(this);
                if (me.val()) {
                    var subjectId = $('select.subjectAudit')
                    var unitKerjaId = $('select.unitKerja');
                    var urlOrigin = subjectId.data('url-origin');
                    var urlParam = $.param({
                        search: 'subject',
                        type_id: me.val(),
                    });
                    subjectId.data('url', decodeURIComponent(decodeURIComponent(urlOrigin + '?' +
                        urlParam)));
                    subjectId.val(null).prop('disabled', false);
                    unitKerjaId.val(null).prop('disabled', true);
                }
                BasePlugin.initSelect2();
            });
        });
    </script>
@endpush

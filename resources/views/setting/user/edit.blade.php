@extends('layouts.modal')

@section('action', rut($routes . '.update', $record->id))

@section('modal-body')
    @method('PATCH')
    <div class="form-group row">
        <label class="col-md-4 col-form-label">{{ __('Nama') }}</label>
        <div class="col-md-8 parent-group">
            <input type="text" name="name" value="{{ $record->name }}" class="form-control"
                placeholder="{{ __('Nama') }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('NIP') }}</label>
        <div class="col-sm-8 parent-group">
            <input @if (in_array("Administator Audit",$record->roles()->pluck('name')->toArray())) disabled

            @endif type="text" name="npp" value="{{ $record->npp }}" class="form-control masking-npp"
                placeholder="{{ __('NIP') }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Username') }}</label>
        <div class="col-sm-8 parent-group">
            <input type="text" name="username" class="form-control" value="{{ $record->username }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Email') }}</label>
        <div class="col-sm-8 parent-group">
            <input type="email" name="email" value="{{ $record->email }}" class="form-control"
                placeholder="{{ __('Email') }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Struktur') }}</label>
        <div class="col-sm-8 parent-group">
            <select name="location_id" class="form-control base-plugin--select2-ajax" id="strukturCtrl"
                data-url="{{ rut('ajax.selectStruct', ['search' => 'position_with_req']) }}"
                placeholder="{{ __('Pilih Salah Satu') }}" @if ($record->position_id == null) disabled @endif>
                <option value="">{{ __('Pilih Salah Satu') }}</option>
                @if ($record->position && $record->position->location_id)
                    <option value="{{ $record->position->location->id }}" selected>{{ $record->position->location->name }}
                    </option>
                @endif
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Jabatan') }}</label>
        <div class="col-sm-8 parent-group">
            {{-- @dump(in_array("Administator Audit",$record->roles()->pluck('name')->toArray())) --}}
            @if (in_array(1,$record->roles()->pluck('id')->toArray()))
                <select id="jabatanCtrl" name="position_id" class="form-control base-plugin--select2-ajax"
                    data-url="{{ rut('ajax.selectPosition', 'all') }}" data-placeholder="{{ __('Pilih Salah Satu') }}"
                    disabled>
                    <option value="">{{ __('Pilih Salah Satu') }}</option>
                    @if ($record->position)
                        <option value="{{ $record->position->id }}" selected>{{ $record->position->name }}</option>
                    @endif
                </select>
            @else
                <select id="jabatanCtrl" name="position_id" class="form-control base-plugin--select2-ajax"
                    data-url="{{ rut('ajax.selectPosition', ['search' => 'by_location', 'id' => $record->position->location->id]) }}"
                    data-placeholder="{{ __('Pilih Salah Satu') }}">
                    <option value="">{{ __('Pilih Salah Satu') }}</option>
                    @if ($record->position)
                        <option value="{{ $record->position->id }}" selected>{{ $record->position->name }}</option>
                    @endif
                </select>
            @endif
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Role') }}</label>
        <div class="col-sm-8 parent-group">
            @if ($record->id == 1)
                @foreach ($record->roles as $role)
                    <input class="d-none" name="roles[]" value="{{ $role->id }}">
                @endforeach
                <select class="form-control base-plugin--select2" data-url="{{ rut('ajax.selectRole', 'all') }}"
                    data-placeholder="{{ __('Pilih Salah Satu') }}" @if ($record->id == 1) disabled @endif>
                    <option value="">{{ __('Pilih Salah Satu') }}</option>
                    @foreach ($record->roles as $val)
                        <option value="{{ $val->id }}" selected>{{ $val->name }}</option>
                    @endforeach
                </select>
            @else
                <select name="roles[]" class="form-control base-plugin--select2-ajax"
                    data-url="{{ rut('ajax.selectRole', 'all') }}" data-placeholder="{{ __('Pilih Salah Satu') }}">
                    <option value="">{{ __('Pilih Salah Satu') }}</option>
                    @foreach ($record->roles as $val)
                        <option value="{{ $val->id }}" selected>{{ $val->name }}</option>
                    @endforeach
                </select>
            @endif
        </div>
    </div>
    @if ($record->id == 1)
        <input type="hidden" name="status" value="active">
        <input type="hidden" name="is_iso_auditor" value="1">
    @else
        <div class="form-group row">
            <label class="col-sm-4 col-form-label">{{ __('Status') }}</label>
            <div class="col-sm-8 parent-group">
                <select name="status" class="form-control base-plugin--select2"
                    placeholder="{{ __('Pilih Salah Satu') }}">
                    <option value="active" {{ $record->status == 'nonactive' ? '' : 'selected' }}>Active</option>
                    <option value="nonactive" {{ $record->status == 'nonactive' ? 'selected' : '' }}>Non Active</option>
                </select>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        $(".masking-npp").inputmask({
            "mask": "9",
            "repeat": 16,
            "greedy": false
        });

        $(function() {
            $('.content-page')
                .on('change', '#strukturCtrl', function() {
                    $.ajax({
                        method: 'GET',
                        url: '{{ yurl('/ajax/jabatan-options') }}',
                        data: {
                            location_id: $(this).val()
                        },
                        success: function(response, state, xhr) {
                            // let options = `<option value='' selected disabled></option>`;
                            let options =
                                `<option disabled selected value=''>Pilih Salah Satu</option>`;
                            for (let item of response) {
                                options += `<option value='${item.id}'>${item.name}</option>`;
                            }
                            $('#jabatanCtrl').select2('destroy');
                            $('#jabatanCtrl').html(options);
                            $('#jabatanCtrl').select2();
                            console.log(54, response, options);
                        },
                        error: function(a, b, c) {
                            console.log(a, b, c);
                        }
                    });
                });
        });
    </script>
@endpush

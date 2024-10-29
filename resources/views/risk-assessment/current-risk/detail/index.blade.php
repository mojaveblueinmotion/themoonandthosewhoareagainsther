@extends('layouts.page', ['container' => 'container'])

@section('card-body')
	@include($views.'.includes.content')
@endsection

@section('card-footer')

@endsection

@section('buttons')

@endsection

@push('scripts')
<script>
    $(function() {
        $('select.complexity,select.volume,select.known_issue,select.chaning_process').change(function(e) {
            var me = $(this);
            if(me.val()) {
                var complexity = $('select.complexity').val();
                var volume = $('select.volume').val();
                var known_issue = $('select.known_issue').val();
                var chaning_process = $('select.chaning_process').val();
                var total_likehood = $('#total_likehood');

                var total = (complexity * 0.3) + (volume * 0.35) + (known_issue * 0.2) + (chaning_process * 0.15)

                total_likehood.prop('value',total.toFixed(1));
            }
        })
    })

    $(function() {
        $('select.materiality,select.legal,select.operational').change(function(e) {
            var me = $(this);
            if(me.val()) {
                var materiality = $('select.materiality').val();
                var legal = $('select.legal').val();
                var operational = $('select.operational').val();
                var total_impact = $('#total_impact');

                var total = (materiality * 0.4) + (legal * 0.3) + (operational * 0.3);

                total_impact.prop('value',total.toFixed(1));
            }
        })
    })
</script>
@endpush

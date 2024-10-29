<script>
    $(function() {
        // initLetterDate();
        initDateStart();
        $("#kodeResikoCtrl").change(function() {
            $.ajax({
                // Change the link to the file you are using
                url: '/risk-assessment/risk-rating/' + $(this).val() + '/getInherentRisk/',
                type: 'get',
                // This just sends the value of the dropdown
                data: {
                    id: $(this).val()
                },
                success: function(response) {
                    var Vals = response;
                    // console.log(response);
                    // These are the inputs that will populate
                    $("#complexity").val(Vals.complexity);
                    $("#volume").val(Vals.volume);
                    $("#known_issue").val(Vals.known_issue);
                    $("#chaning_process").val(Vals.chaning_process);

                    $("#materiality").val(Vals.materiality);
                    $("#legal").val(Vals.legal);
                    $("#operational").val(Vals.operational);
                }
            });
        });

        $("#kodeResikoCtrlCurrent").change(function() {
            $.ajax({
                // Change the link to the file you are using
                url: '/risk-assessment/risk-rating/' + $(this).val() + '/getCurrentRisk/',
                type: 'get',
                // This just sends the value of the dropdown
                data: {
                    id: $(this).val()
                },
                success: function(response) {
                    var Vals = response;
                    // console.log(response);
                    // These are the inputs that will populate
                    $("#complexity_current").val(Vals.complexity);
                    $("#volume_current").val(Vals.volume);
                    $("#known_issue_current").val(Vals.known_issue);
                    $("#chaning_process_current").val(Vals.chaning_process);

                    $("#materiality_current").val(Vals.materiality);
                    $("#legal_current").val(Vals.legal);
                    $("#operational_current").val(Vals.operational);
                }
            });
        });
    });
    var romanNumber = function(no) {
        var numbers = ['I', 'II', 'II', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        return numbers[no];
    }

    var initLetterDate = function() {
        $('.content-page').on('changeDate', 'input.letter_date', function() {
            var me = $(this);
            if (me.val()) {
                // Set Letter No
                var month = romanNumber(me.datepicker('getDate').getMonth());
                var year = me.datepicker('getDate').getFullYear();
                var letter_no = me.closest('form').find('input.letter_no');

                var formated = letter_no.data('format')
                    .replace('[NO]', letter_no.data('no'))
                    .replace('[MONTH]', month)
                    .replace('[YEAR]', year);

                letter_no.val(formated);

                refreshAuditDate();
            }
        });
        $('input.letter_date').trigger('changeDate');
    }

    var refreshAuditDate = function() {
        var el_letter = $('input.letter_date');
        var el_start = $('input.date_start');
        var el_end = $('input.date_end');

        if (el_letter.length && el_start.length && el_end.length) {
            var opt_start = el_start.data('options');
            var opt_end = el_end.data('options');

            el_start.datepicker('update', el_start.val())
                .datepicker('setStartDate', opt_start.startDate);

            // Jika letter date > validation startDate of datepicker : ubah startDate-nya
            if (toTime(el_letter.val()) > toTime(opt_start.startDate)) {
                el_start.datepicker('setStartDate', el_letter.val());

                if (el_start.val() && toTime(el_start.val()) >= toTime(el_letter.val())) {
                    el_start.datepicker('update', el_start.val());
                } else {
                    el_start.datepicker('update', '');
                    el_end.datepicker('update', '').prop('disabled', true);
                }
            }

        }
    }

    var toTime = function(date) {
        var ds = date.split('/');
        var year = ds[2];
        var month = ds[1];
        var day = ds[0];
        return new Date(year + '-' + month + '-' + day).getTime();
    }

    var initDateStart = function() {
        $('.content-page').on('changeDate', 'input.date_start', function(value) {
            var me = $(this);
            if (me.val()) {
                var startDate = new Date(value.date.valueOf());
                var date_end = me.closest('.input-group').find('input.date_end');
                date_end.prop('disabled', false)
                    .val(me.val())
                    .datepicker('setStartDate', startDate)
                    .focus();
            }
        });
    }
</script>

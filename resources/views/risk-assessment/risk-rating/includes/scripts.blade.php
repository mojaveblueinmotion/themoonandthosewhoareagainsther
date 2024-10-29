<script>
	$(function () {
		initDateStart();
		$("#subProcessCtrl").change(function() {
			$.ajax({
				// Change the link to the file you are using
				url: '/risk-assessment/risk-rating/getInherentRisk/',
				type: 'get',
				// This just sends the value of the dropdown
				data: {
					risk_register_id: $('#riskRegisterIdCtrl').val(),
					main_process_id: $('#kodeResikoCtrl').val(),
					sub_process_id: $('#subProcessCtrl').val(),
				},
				success: function(response) {
					var Vals = response;
					console.log(response);
					$("#objective").val(Vals.risk_register_detail.objective).trigger('summernote.change');
					$("#peristiwa").val(Vals.risk_register_detail.peristiwa).trigger('summernote.change');
					$("#penyebab").val(Vals.risk_register_detail.penyebab).trigger('summernote.change');
					$("#dampak").val(Vals.risk_register_detail.dampak).trigger('summernote.change');
					// These are the inputs that will populate
					$("#complexity").val(Vals.complexity);
					$("#volume").val(Vals.volume);
					$("#known_issue").val(Vals.known_issue);
					$("#changing_process").val(Vals.chaning_process);
					$("#total_score_likelihood").val(Vals.total_likehood);

					$("#materiality").val(Vals.materiality);
					$("#legal").val(Vals.legal);
					$("#operational").val(Vals.operational);
					$("#total_score_impact").val(Vals.total_impact);

				}
			});
		});

		$("#subProcessCtrlCurrent").change(function() {
			$.ajax({
				// Change the link to the file you are using
				url: '/risk-assessment/risk-rating/getCurrentRisk/',
				type: 'get',
				// This just sends the value of the dropdown
				data: {
					risk_register_id: $('#riskRegisterIdCtrl').val(),
					main_process_id: $('#kodeResikoCtrlCurrent').val(),
					sub_process_id: $('#subProcessCtrlCurrent').val(),
				},
				success: function(response) {
					var Vals = response;
					console.log(response);
					$("#objective_2").val(Vals.risk_register_detail.objective).trigger('summernote.change');
					$("#peristiwa_2").val(Vals.risk_register_detail.peristiwa).trigger('summernote.change');
					$("#penyebab_2").val(Vals.risk_register_detail.penyebab).trigger('summernote.change');
					$("#dampak_2").val(Vals.risk_register_detail.dampak).trigger('summernote.change');
					// These are the inputs that will populate

					$("#complexity_2").val(Vals.complexity);
					$("#volume_2").val(Vals.volume);
					$("#known_issue_2").val(Vals.known_issue);
					$("#changing_process_2").val(Vals.chaning_process);
					$("#total_score_likelihood_2").val(Vals.total_likehood);

					$("#materiality_2").val(Vals.materiality);
					$("#legal_2").val(Vals.legal);
					$("#operational_2").val(Vals.operational);
					$("#total_score_impact_2").val(Vals.total_impact);

				}
			});
		});

		$('.content-page').on('change', 'select#kodeResikoCtrl', function(e) {
            var me = $(this);
            if (me.val()) {
                var subProcess = $('select.sub_process_id_detail');
                var urlOrigin = subProcess.data('url-origin');
                console.log(subProcess);
                var urlParam = $.param({
                    main_process_id: me.val(),
                });
                console.log(urlOrigin + '?' +
                    urlParam)
                subProcess.data('url', decodeURIComponent(decodeURIComponent(urlOrigin + '?' +
                    urlParam)));
                subProcess.val(null).prop('disabled', false);
            }
            BasePlugin.initSelect2();
        });

		$('.content-page').on('change', 'select#kodeResikoCtrlCurrent', function(e) {
            var me = $(this);
            if (me.val()) {
                var subProcess = $('select.sub_process_id_detail_current');
                var urlOrigin = subProcess.data('url-origin');
                console.log(subProcess);
                var urlParam = $.param({
                    main_process_id: me.val(),
                });
                console.log(urlOrigin + '?' +
                    urlParam)
                subProcess.data('url', decodeURIComponent(decodeURIComponent(urlOrigin + '?' +
                    urlParam)));
                subProcess.val(null).prop('disabled', false);
            }
            BasePlugin.initSelect2();
        });
	});
</script>

<script>
	var romanNumber = function (no) {
		var numbers = ['I','II','II','IV','V','VI','VII','VIII','IX','X', 'XI', 'XII'];
		return numbers[no];
	}

	var initLetterDate = function () {
		$('.content-page').on('changeDate', 'input.letter_date', function () {
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

	var refreshAuditDate = function () {
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
				}
				else {
					el_start.datepicker('update', '');
					el_end.datepicker('update', '').prop('disabled', true);
				}
			}

		}
	}

	var toTime = function (date) {
		var ds = date.split('/');
		var year = ds[2];
		var month = ds[1];
		var day = ds[0];
		return new Date(year+'-'+month+'-'+day).getTime();
	}

	var initDateStart = function () {
		$('.content-page').on('changeDate', 'input.date_start', function (value) {
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

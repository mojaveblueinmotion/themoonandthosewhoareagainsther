<script>
	$(function () {
		initDateStart();
	});
</script>

<script>
	var initDateStart = function () {
		$('.content-page').on('changeDate', 'input.date_start', function (value) {
			var me = $(this);
			if (me.val()) {
				var startDate = new Date(value.date.valueOf());
				var date_end = $('input.date_end');
				// var date_end = me.closest('.input-group').find('input.date_end');
				date_end.prop('disabled', false)
						.val(me.val())
						.datepicker('setStartDate', startDate)
						.focus();
			}
		});
	}
</script>

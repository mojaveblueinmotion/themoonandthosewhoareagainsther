<script>
	$(function () {

        $('.content-page').on('keyup', '#total', function(e) {
            countSaldo();
        });
		
        $('.modal-dialog').removeClass('modal-md').addClass('modal-xl');

        $('.content-page').on('change', '#tipe', function(e) {
            countSaldo();
        });
	});
</script>
<script>
	var countSaldo = function () {
		var saldo_sisa = $('#saldo_sisa');
		var saldo_db = $('#sisaSaldoDb');
		var total = $('#total');
		var count_detail = $('#countDetail');
		var tipe = $('#tipe');

		console.log('Tipe:', tipe.val());
		console.log('Saldo DB:', saldo_db.val());
		console.log('Total:', total.val());

		let saldoValue = parseInt(saldo_db.val().replace(/,/g, '')) || 0; // Default to 0 if NaN
		let totalValue = parseInt(total.val().replace(/,/g, '')) || 0; // Default to 0 if NaN

		if(count_detail.val() == 0){
			if (tipe.val() == 1) {
				saldo_sisa.val(0);
			} else {
				saldo_sisa.val(saldoValue + totalValue);
			}
		}else if(saldoValue < totalValue){
			if (tipe.val() == 1) {
				saldo_sisa.val(0);
			} else {
				saldo_sisa.val(saldoValue + totalValue);
			}
		}else{
			if (tipe.val() == 1) {
				saldo_sisa.val(saldoValue - totalValue);
			} else {
				saldo_sisa.val(saldoValue + totalValue);
			}
		}
		

		// Optional: Log the result for debugging
		console.log('Saldo Sisa:', saldo_sisa.val());
	};
</script>
<script>
	$(function () {
		$(".totalDetail").on("keyup", countPembayaran);
		$(".masking-code").inputmask({
            "mask": "9",
            "repeat": 3,
            "greedy": false
        });

        // Timbangan
        $('.content-page').on('keyup', '#gross, #tere, #refaksi', function(e) {
            var gross = $('#gross');
            var tere = $('#tere');

            var bruto = $('#bruto');
            var refaksi = $('#refaksi');

            var potongan = $('#potongan');
            var netto = $('#netto');

            if(gross.val() && tere.val()){
                bruto.val(gross.val().replace(/,/g, '') - tere.val().replace(/,/g, ''));
            }
            if(bruto.val() && refaksi.val()){
                potongan.val(bruto.val().replace(/,/g, '') * (refaksi.val().replace(/,/g, '') / 100));
            }
        });

        $('.content-page').on('keyup', '#potongan', function(e) {
            var gross = $('#gross');
            var tere = $('#tere');

            // gross - tere = bruto
            // bruto * refaksi(%) = potongan
            // bruto - potongan = netto
            var bruto = $('#bruto');
            var refaksi = $('#refaksi');

            var potongan = $('#potongan');
            var netto = $('#netto');

            if(bruto.val() && potongan.val()){
                netto.val(bruto.val().replace(/,/g, '') - potongan.val().replace(/,/g, ''))
            }
        });
        $('.modal-dialog').removeClass('modal-md').addClass('modal-xl');
        
        // Pembayaran
        $('.content-page').on('keyup', '#harga, #netto', function(e) {
            var harga = $('#harga');
            var netto = $('#netto');

            // harga * netto = jumlah
            var jumlah = $('#jumlah');

            if(netto.val() && harga.val()){
                jumlah.val(netto.val().replace(/,/g, '') * harga.val().replace(/,/g, ''))
            }
        });

        $('.content-page').on('keyup', '#harga, #jumlah, #biaya_bongkar_ampera, #fee_agen_bruto, #fee_agen', function(e) {
            countPembayaran();
        });
		// handleExtPart();
	});
</script>
<script>
	var countPembayaran = function () {
		var jumlah = $('#jumlah');
		var biaya_bongkar_ampera = $('#biaya_bongkar_ampera');
		var fee_agen_bruto = $('#fee_agen_bruto');
		var fee_agen = $('#fee_agen');
		var total_dibayar = $('#total_dibayar');
		var hasil_akhir = $('#hasil_akhir');

		// semua ditambahkan = total_dibayar & pengeluaran lapak

		let totalSum = 0;
		$(".totalDetail").each(function() {
			const value = parseInt($(this).val().replace(/,/g, '')) || 0; // Fallback to 0 if NaN
			totalSum += value; // Add to the total sum
		});
		total_dibayar.val(parseInt(jumlah.val().replace(/,/g, '')) - parseInt(biaya_bongkar_ampera.val().replace(/,/g, ''))
			- parseInt(fee_agen_bruto.val().replace(/,/g, ''))
			- parseInt(fee_agen.val().replace(/,/g, '')) - parseInt(totalSum))
		hasil_akhir.val(parseInt(jumlah.val().replace(/,/g, '')) - parseInt(biaya_bongkar_ampera.val().replace(/,/g, ''))
			- parseInt(fee_agen_bruto.val().replace(/,/g, ''))
			- parseInt(fee_agen.val().replace(/,/g, '')) - parseInt(totalSum))
	};

	
	// var handleExtPart = function () {
	// 	$('.content-page').on('click', '.add-ext-part', function (e) {
	// 		var me = $(this),
	// 			tbody = me.closest('table').find('tbody').first(),
	// 			key = tbody.find('tr').length ? (tbody.find('tr').last().data('key') + 1) : 1;

	// 		var template = `
	// 			<tr data-key="`+key+`">
	// 				<td class="width-40px no text-center">`+key+`</td>
	// 				<td class="parent-group text-left">
	// 					<select name="parts[`+key+`][name]" class="form-control base-plugin--select2-ajax"
	// 						data-url="{{ rut('ajax.selectPembayaran', ['search' => 'all']) }}"
	// 						data-url-origin="{{ rut('ajax.selectPembayaran', ['search' => 'all']) }}"
	// 						placeholder="{{ __('Pilih Salah Satu ') }}">
	// 						<option value="">{{ __('Pembayaran') }}</option>
	// 					</select>
	// 				</td>
	// 				<td class="parent-group text-left">
	// 					<div class="input-group">
	// 						<div class="input-group-prepend">
	// 							<span class="input-group-text font-weight-bolder">Rp</span>
	// 						</div>
	// 						<input value="0" class="form-control base-plugin--inputmask_currency totalDetail" name="parts[`+key+`][total]"
	// 							placeholder="{{ __('Total') }}">
	// 					</div>
	// 				</td>
	// 				<td class="valign-top width-30px text-center">
	// 					<button type="button"
	// 						class="btn btn-sm btn-icon btn-circle btn-danger remove-ext-part">
	// 						<i class="fa fa-trash"></i>
	// 					</button>
	// 				</td>
	// 			</tr>
	// 		`;

	// 		tbody.append(template);
	// 		// BasePlugin.init();
	// 	});
		
	// 	$('.content-page').on('click', '.remove-ext-part', function (e) {
	// 		var me = $(this),
	// 			tbody = me.closest('table').find('tbody').first();

	// 		me.closest('tr').remove();
	// 		tbody.find('.no').each(function (i, val) {
	// 			$(this).html(i+1);
	// 		});
	// 	});
	// }
</script>
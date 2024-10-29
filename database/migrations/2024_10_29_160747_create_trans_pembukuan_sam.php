<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(
            'ref_kendaraan',
            function (Blueprint $table) {
                $table->id();
                $table->text('name');
                $table->text('no_kendaraan')->nullable();
                $table->text('description')->nullable();
                $table->commonFields();
            }
        );

        Schema::create(
            'trans_pembukuan_sam',
            function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('perusahaan_id');
                $table->date('month')->nullable();

                $table->string('status')->default('new');
                $table->longText('upgrade_reject')->nullable();
                $table->smallInteger('version')->default(0);
                $table->commonFields();
            }
        );

        Schema::create(
            'trans_detaiL_pembukuan_sam',
            function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('pembukuan_sam_id');
                $table->unsignedBigInteger('kendaraan_id')->nullable();
                $table->text('no_timbangan')->nullable();
                $table->date('tgl_masuk')->nullable();
                $table->date('kirim_pabrik')->nullable();

                $table->text('supplier')->nullable();

                $table->text('gross')->nullable();
                $table->text('tere')->nullable();
                $table->text('bruto')->nullable();
                $table->text('refaksi')->nullable();
                $table->text('potongan')->nullable();
                $table->text('netto')->nullable();
                $table->text('harga')->nullable();
                $table->text('jumlah')->nullable();
                $table->text('biaya_bongkar_ampera')->nullable();
                $table->text('fee_agen')->nullable();
                $table->text('fee_agen_bruto')->nullable();
                $table->text('total_dibayar')->nullable();
                $table->text('hasil_akhir')->nullable();

                $table->string('status')->default('new');
                $table->commonFields();
            }
        );

        Schema::create('trans_detaiL_pembukuan_sam_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('detail_id');
            $table->unsignedBigInteger('pembayaran_id')->nullable();
            $table->text('total')->nullable();
            $table->commonFields();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('ref_kendaraan');
        Schema::drop('trans_detaiL_pembukuan_sam');
        Schema::drop('trans_pembukuan_sam');
    }
};

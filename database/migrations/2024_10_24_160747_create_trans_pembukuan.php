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
            'ref_lapak',
            function (Blueprint $table) {
                $table->id();
                $table->text('name');
                $table->text('description')->nullable();
                $table->commonFields();
            }
        );

        Schema::create(
            'ref_pembayaran_lainnya',
            function (Blueprint $table) {
                $table->id();
                $table->text('name');
                $table->text('description')->nullable();
                $table->commonFields();
            }
        );

        Schema::create(
            'trans_pembukuan_lapak',
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
            'trans_detaiL_pembukuan_lapak',
            function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('pembukuan_lapak_id');
                $table->text('no_timbangan')->nullable();
                $table->date('tgl_masuk')->nullable();
                $table->date('kirim_pabrik')->nullable();

                $table->text('vendor')->nullable();
                $table->text('gross')->nullable();
                $table->text('tere')->nullable();
                $table->text('bruto')->nullable();
                $table->text('refaksi')->nullable();
                $table->text('potongan')->nullable();
                $table->text('netto')->nullable();
                $table->text('harga')->nullable();
                $table->text('jumlah')->nullable();
                $table->text('biaya_bongkar_ampera')->nullable();
                $table->text('premi_supir')->nullable();
                $table->text('premi_agen')->nullable();
                $table->text('total_dibayar')->nullable();
                $table->text('bongkaran')->nullable();
                $table->text('pengeluaran_lapak')->nullable();

                $table->string('status')->default('new');
                $table->commonFields();
            }
        );

        Schema::create('trans_detaiL_pembukuan_lapak_pembayaran', function (Blueprint $table) {
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
        Schema::drop('ref_perusahaan');
        Schema::drop('ref_pembayaran_lainnya');
        Schema::drop('trans_detaiL_pembukuan_lapak');
        Schema::drop('trans_pembukuan_lapak');
    }
};

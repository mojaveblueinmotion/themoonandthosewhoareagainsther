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
            'trans_loader',
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
            'trans_detaiL_loader',
            function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('loader_id');
                $table->date('tgl_input')->nullable();

                $table->text('keterangan')->nullable();
                $table->unsignedBigInteger('tipe')->nullable();
                $table->text('total')->nullable();
                $table->text('saldo_sisa')->nullable();

                $table->text('description')->nullable();

                $table->string('status')->default('new');
                $table->commonFields();
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('trans_detaiL_loader');
        Schema::drop('trans_loader');
    }
};

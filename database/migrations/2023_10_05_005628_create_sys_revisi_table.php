<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysRevisiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'sys_revisi',
            function (Blueprint $table) {
                $table->id();
                $table->morphs('target');
                $table->string('module')->nullable();
                $table->smallInteger('version')->default(0);
                $table->string('file_path');
                $table->string('flag')->nullable();
                $table->commonFields();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sys_revisi');
    }
}

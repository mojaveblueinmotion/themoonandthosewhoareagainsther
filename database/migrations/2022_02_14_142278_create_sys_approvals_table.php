<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'sys_approval',
            function (Blueprint $table) {
                $table->id();
                $table->tinyInteger('version')->default(0);
                $table->morphs('target');
                $table->string('module');
                $table->text('note')->nullable();
                $table->commonFields();
            }
        );

        Schema::create(
            'sys_approval_detail',
            function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('approval_id');
                $table->unsignedBigInteger('role_id');
                $table->smallInteger('type')->default(1)->comment('1:sequence/berurutan, 2:pararel/berbarengan');
                $table->integer('order')->default(1);
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('position_id')->nullable();
                $table->mediumText('note')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->string('status')->default('new');
                $table->commonFields();

                $table->foreign('approval_id')->references('id')->on('sys_approval');
                $table->foreign('role_id')->references('id')->on('sys_roles');
                $table->foreign('user_id')->references('id')->on('sys_users');
                $table->foreign('position_id')->references('id')->on('ref_positions');
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
        Schema::drop('sys_approval_detail');
        Schema::drop('sys_approval');
    }
}

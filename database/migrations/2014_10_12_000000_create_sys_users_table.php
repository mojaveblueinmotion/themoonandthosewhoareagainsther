<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_users', function (Blueprint $table) {
            $table->id();
            $table->string('type', 16)
                ->comment('internal, provider')
                ->default('internal')->index();
            $table->string('jabatan_provider')->nullable();
            $table->string('name');
            $table->string('npp')->nullable();
            $table->string('username')->nullable()->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('nik')->nullable();
            $table->string('image')->nullable();
            $table->string('phone')->nullable();
            $table->string('status')
                ->default('active')
                ->comment('active|nonactive');
            $table->rememberToken();
            $table->commonFields();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sys_users');
    }
}

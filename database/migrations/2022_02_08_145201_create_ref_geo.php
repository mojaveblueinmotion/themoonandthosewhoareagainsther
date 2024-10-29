<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefGeo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create table for storing provinsi
        Schema::create(
            'ref_province',
            function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('code')->nullable()->unique();
                $table->integer('created_by')->unsigned()->nullable();
                $table->integer('updated_by')->unsigned()->nullable();
                $table->timestamps();
            }
        );

        // Create table for storing kabupaten/kota
        Schema::create(
            'ref_city',
            function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('province_id');
                $table->string('name');
                $table->string('code')->nullable();
                $table->integer('created_by')->unsigned()->nullable();
                $table->integer('updated_by')->unsigned()->nullable();
                $table->timestamps();

                $table->foreign('province_id')->references('id')->on('ref_province');
            }
        );

        // Create table for storing kabupaten/kota
        Schema::create(
            'ref_district',
            function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('city_id');
                $table->string('name');
                $table->string('code')->nullable();
                $table->integer('created_by')->unsigned()->nullable();
                $table->integer('updated_by')->unsigned()->nullable();
                $table->timestamps();

                $table->foreign('city_id')->references('id')->on('ref_city');
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
        Schema::drop('ref_district');
        Schema::drop('ref_city');
        Schema::drop('ref_province');
    }
}

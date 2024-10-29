<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefOrgStructsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'ref_org_structs',
            function (Blueprint $table) {
                $table->id();
                $table->string('year')->nullable();
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->string('level')->comment('root, bod, division, branch');
                $table->unsignedTinyInteger('type')->default(0)
                    ->comment('1:presdir, 2:direktur finance, 3:ia division, 4:it division');
                $table->string('name');
                $table->string('email')->nullable();
                $table->string('website')->nullable();
                $table->string('code', 32)->index()->nullable();
                $table->string('phone', 20)->nullable();
                $table->text('description')->nullable();
                $table->text('address')->nullable();
                $table->unsignedBigInteger('province_id')->nullable();
                $table->unsignedBigInteger('city_id')->nullable();
                $table->commonFields();

                $table->foreign('parent_id')
                    ->references('id')->on('ref_org_structs');
                $table->foreign('province_id')
                    ->references('id')->on('ref_province');
                $table->foreign('city_id')
                    ->references('id')->on('ref_city');
            }
        );

        Schema::create('ref_org_structs_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('struct_id');

            $table->foreign('group_id')->references('id')->on('ref_org_structs')->onDelete('cascade');
            $table->foreign('struct_id')->references('id')->on('ref_org_structs');

            $table->unique(['group_id', 'struct_id']);
        });

        Schema::create('ref_level_positions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->commonFields();
        });
        Schema::create(
            'ref_positions',
            function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('location_id')->nullable();
                $table->unsignedBigInteger('level_id')->nullable();
                $table->string('name');
                $table->unsignedInteger('code');
                $table->commonFields();

                $table->foreign('location_id')->references('id')->on('ref_org_structs');
                $table->foreign('level_id')->references('id')->on('ref_level_positions')->onDelete('cascade');
            }
        );

        Schema::table(
            'sys_users',
            function (Blueprint $table) {
                $table->unsignedBigInteger('provider_id')->nullable();
                $table->unsignedBigInteger('position_id')->nullable()->after('password');

                $table->foreign('position_id')->references('id')->on('ref_positions');
                $table->foreign('provider_id')
                    ->references('id')
                    ->on('ref_org_structs');
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
        Schema::table('sys_users', function (Blueprint $table) {
            $table->dropForeign(['position_id']);
            $table->dropColumn(['position_id']);
        });

        Schema::drop('ref_positions');
        Schema::drop('ref_org_structs_groups');
        Schema::drop('ref_org_structs');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WarehouseDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_documents', function(Blueprint  $table) {
            $table->increments('id');
            $table->string('numberDocument');
            $table->string('name');
            $table->string('address')->nullable();;
            $table->string('condition')->nullable();;
            $table->string('department_id')->nullable();;
            $table->string('district_id')->nullable();;
            $table->string('location_id')->nullable();;
            $table->string('province_id')->nullable();;
            $table->string('state')->nullable();;
            $table->string('trade_name')->nullable();;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_documents');
    }
}

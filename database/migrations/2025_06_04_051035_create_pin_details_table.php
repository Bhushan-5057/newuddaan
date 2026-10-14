<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePinDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('pin_details', function (Blueprint $table) {
            $table->id();
            $table->string('pin_type', 50)->nullable();
            $table->string('package_name')->nullable();
            $table->integer('vip')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->longText('custom_fields')->nullable();
            $table->timestamp('added_on')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_on')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    public function down()
    {
        Schema::dropIfExists('pin_details');
    }
}

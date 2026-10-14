<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvancedInfoTable extends Migration
{
    public function up()
    {
        Schema::create('advanced_info', function (Blueprint $table) {
            $table->id();
            $table->longText('title')->nullable();
            $table->longText('name')->nullable();
            $table->longText('label')->nullable();
            $table->mediumText('type')->nullable();
            $table->longText('options')->nullable();
            $table->longText('image')->nullable();
            $table->longText('value')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('admin_status')->default(0);
            $table->dateTime('added_on')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    public function down()
    {
        Schema::dropIfExists('advanced_info');
    }
}
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePinHistoryTable extends Migration
{
 public function up()
    {
        Schema::create('pin_history', function (Blueprint $table) {
            $table->id();
            $table->string('user_id', 200)->nullable();     // You can change this to unsignedBigInteger if it's a foreign key
            $table->integer('tx_user')->nullable();         // You can also convert this to unsignedBigInteger if needed
            $table->integer('credit')->default(0);
            $table->integer('debit')->default(0);
            $table->string('pin_type', 200)->nullable();
            $table->longText('tx_type')->nullable();
            $table->longText('remark')->nullable();
            $table->string('prev_pin', 50)->nullable();
            $table->string('curr_pin', 200)->nullable();
            $table->tinyInteger('retrieve_status')->default(0);
            $table->timestamp('updated_on')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('added_on')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    public function down()
    {
        Schema::dropIfExists('pin_history');
    }
}

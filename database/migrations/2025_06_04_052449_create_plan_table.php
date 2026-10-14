<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanTable extends Migration
{
     public function up()
    {
        Schema::create('plan', function (Blueprint $table) {
            $table->id();
            $table->string('package_name', 50);
            $table->string('rank', 20)->nullable();
            $table->double('level_income')->nullable();
            $table->double('referral_income');
            $table->double('direct_income')->default(0);
            $table->integer('capping');
            $table->integer('team_required')->default(0);
            $table->double('left_team')->nullable();
            $table->double('right_team')->nullable();
            $table->integer('reward_days')->default(0);
            $table->string('reward', 244)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->longText('custom_fields')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('plan');
    }
}

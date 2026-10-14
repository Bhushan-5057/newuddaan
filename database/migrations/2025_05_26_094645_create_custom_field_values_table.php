<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('custom_field_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->json('custom_field_ids')->nullable();
            $table->json('value')->nullable();
            $table->string('module_name')->default('register');
            $table->timestamps();

            // Add foreign key constraint (optional, depending on your app structure)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('custom_field_values');
    }
};
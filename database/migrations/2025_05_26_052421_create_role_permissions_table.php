<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('role');
            $table->string('feature');
            $table->boolean('can_view')->default(0);
            $table->boolean('can_create')->default(0);
            $table->boolean('can_edit')->default(0);
            $table->boolean('can_delete')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('role_permissions');
    }
};
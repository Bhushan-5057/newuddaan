<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // This creates a bigint(20) UNSIGNED NOT NULL auto-incrementing primary key
            $table->string('sponsor_id', 255)->nullable();
            $table->string('name', 255);
            $table->string('email', 255);
            $table->string('username', 255);
            $table->string('profile_image', 255)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role', 255)->default('user');
            $table->rememberToken(); // This creates a varchar(100) nullable remember_token
            $table->timestamps(); // This creates created_at and updated_at timestamp columns
            
            // For the JSON column with validation
            $table->json('custom_fields')->nullable();
            
            // Add indexes
            $table->unique('email');
            $table->unique('username');
            $table->index('sponsor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
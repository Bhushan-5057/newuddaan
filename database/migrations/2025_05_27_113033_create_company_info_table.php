<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
public function up()
    {
        Schema::create('company_info', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('label', 100)->nullable();
            $table->string('value', 500)->nullable();
            $table->string('type')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamp('added_on')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('company_info');
    }
};
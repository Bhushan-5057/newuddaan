<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_types', function (Blueprint $table) {
            $table->increments('id'); // int(11) NOT NULL auto_increment
            $table->string('name', 250)->nullable(); // varchar(250) DEFAULT NULL
            $table->string('slug', 255)->nullable(); // varchar(255) DEFAULT NULL
            $table->string('count_in_wallet', 255)->nullable(); // varchar(255) DEFAULT NULL
            $table->string('wallet_type', 250)->nullable(); // varchar(250) DEFAULT NULL
            $table->tinyInteger('status')->default(1); // tinyint(4) NOT NULL DEFAULT 1
            $table->string('wallet_column', 55)->nullable(); // varchar(55) DEFAULT NULL
            $table->tinyInteger('binary')->default(1); // tinyint(4) NOT NULL DEFAULT 1
            $table->tinyInteger('single_leg')->default(1); // tinyint(4) NOT NULL DEFAULT 1
            $table->tinyInteger('generation')->default(1); // tinyint(4) NOT NULL DEFAULT 1
            $table->integer('product_base')->default(1); // int(11) NOT NULL DEFAULT 1
            $table->tinyInteger('universal')->nullable(); // tinyint(4) DEFAULT NULL
            $table->tinyInteger('investment')->nullable(); // tinyint(4) DEFAULT NULL
            $table->tinyInteger('repurchase')->nullable(); // tinyint(4) DEFAULT NULL
            $table->tinyInteger('ecommerce')->nullable(); // tinyint(4) DEFAULT NULL
            $table->timestamp('added_on')->useCurrent(); // timestamp NOT NULL DEFAULT current_timestamp()
            $table->timestamp('updated_on')->useCurrent()->useCurrentOnUpdate(); // timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallet_types');
    }
}
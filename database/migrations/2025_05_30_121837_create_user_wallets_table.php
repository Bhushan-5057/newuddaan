<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_wallets', function (Blueprint $table) {
            $table->increments('id'); // int(11) NOT NULL, auto-incrementing primary key
            $table->integer('u_code')->nullable(); // int(11) DEFAULT NULL
            $table->integer('active_id')->default(0); // int(11) DEFAULT 0
            $table->double('c1')->default(0); // double DEFAULT 0
            $table->double('c2')->default(0); // double DEFAULT 0
            $table->double('c3')->default(0); // double DEFAULT 0
            $table->double('c4')->default(0); // double DEFAULT 0
            $table->double('c5')->default(0); // double DEFAULT 0
            $table->double('c6')->default(0); // double DEFAULT 0
            $table->double('c7')->default(0); // double DEFAULT 0
            $table->double('c8')->default(0); // double DEFAULT 0
            $table->double('c9')->default(0); // double DEFAULT 0
            $table->double('c10')->default(0); // double DEFAULT 0
            $table->double('c11')->default(0); // double DEFAULT 0
            $table->double('c12')->default(0); // double DEFAULT 0
            $table->double('c13')->default(0); // double DEFAULT 0
            $table->double('c14')->default(0); // double DEFAULT 0
            $table->double('c15')->default(0); // double DEFAULT 0
            $table->double('c16')->default(0); // double DEFAULT 0
            $table->double('c17')->default(0); // double DEFAULT 0
            $table->double('c18')->default(0); // double DEFAULT 0
            $table->double('c19')->default(0); // double DEFAULT 0
            $table->double('c20')->default(0); // double DEFAULT 0
            $table->double('c21')->default(0); // double NOT NULL DEFAULT 0
            $table->double('c22')->default(0); // double NOT NULL DEFAULT 0
            $table->double('c23')->default(0); // double NOT NULL DEFAULT 0
            $table->double('c24')->default(0); // double NOT NULL DEFAULT 0
            $table->double('c25')->default(0); // double NOT NULL DEFAULT 0
            $table->double('c26')->default(0); // double NOT NULL DEFAULT 0
            $table->double('c27')->default(0); // double NOT NULL DEFAULT 0
            $table->double('c28')->default(0); // double NOT NULL DEFAULT 0
            $table->double('c29')->default(0); // double NOT NULL DEFAULT 0
            $table->double('c30')->default(0); // double DEFAULT 0

            // Note: MyISAM engine is not directly specified in Laravel migrations.
            // Laravel defaults to InnoDB. If MyISAM is strictly required,
            // you might need to configure it in your database config or manually.
            // For most modern Laravel apps, InnoDB is preferred.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_wallets');
    }
}
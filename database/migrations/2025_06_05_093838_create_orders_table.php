<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id'); // int(11) NOT NULL auto_increment
            $table->string('u_code', 255)->nullable(); // varchar(255) DEFAULT NULL
            $table->integer('active_id')->nullable(); // int(11) DEFAULT NULL
            $table->string('tx_type', 255)->nullable(); // varchar(255) DEFAULT NULL
            $table->integer('tx_id')->nullable(); // int(11) DEFAULT NULL
            $table->string('tx_user_id', 55)->nullable(); // varchar(55) DEFAULT NULL
            $table->double('order_mrp')->default(0); // double NOT NULL DEFAULT 0
            $table->double('order_amount')->default(0); // double NOT NULL DEFAULT 0
            $table->string('package_name', 250); // varchar(250) NOT NULL
            $table->double('order_bv')->nullable(); // double DEFAULT NULL
            $table->double('pv')->nullable(); // double DEFAULT NULL
            $table->double('roi'); // double NOT NULL
            $table->double('times'); // double NOT NULL
            $table->integer('roi_bv'); // int(11) NOT NULL
            $table->dateTime('expire_date'); // datetime NOT NULL
            $table->longText('order_details')->nullable(); // longtext DEFAULT NULL
            $table->longText('order_address')->nullable(); // longtext DEFAULT NULL
            $table->tinyInteger('status')->default(0); // tinyint(4) NOT NULL DEFAULT 0
            $table->tinyInteger('payment_status')->default(0); // tinyint(4) NOT NULL DEFAULT 0
            $table->longText('payment_response')->nullable()->comment('payment slip too'); // longtext DEFAULT NULL
            $table->integer('payout_id')->nullable(); // int(11) DEFAULT NULL
            $table->tinyInteger('payout_status')->default(0); // tinyint(4) NOT NULL DEFAULT 0
            $table->integer('closing_status')->default(0); // int(11) NOT NULL DEFAULT 0
            $table->integer('invoice_no')->nullable(); // int(11) DEFAULT NULL
            $table->timestamp('date_accept')->nullable(); // timestamp NULL DEFAULT NULL
            $table->timestamp('date_dispatch')->nullable(); // timestamp NULL DEFAULT NULL
            $table->timestamp('date_approve')->nullable(); // timestamp NULL DEFAULT NULL
            $table->timestamp('date_reject')->nullable(); // timestamp NULL DEFAULT NULL
            $table->timestamp('added_on')->useCurrent(); // timestamp NOT NULL DEFAULT current_timestamp()
            $table->dateTime('expire_on')->nullable(); // datetime DEFAULT NULL
            $table->timestamp('updated_on')->useCurrent()->useCurrentOnUpdate(); // timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()

            // Laravel defaults to InnoDB and utf8mb4. If you need MyISAM,
            // you'd typically set it in config/database.php or add:
            // $table->engine = 'MyISAM';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
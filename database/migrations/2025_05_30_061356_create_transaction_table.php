<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction', function (Blueprint $table) {
            $table->increments('id'); // int(11) NOT NULL, auto-incrementing primary key
            $table->string('tx_u_code', 25)->nullable(); // varchar(25) DEFAULT NULL
            $table->string('u_code', 25)->nullable(); // varchar(25) DEFAULT NULL
            $table->string('tx_type', 100)->nullable(); // varchar(100) DEFAULT NULL
            $table->string('debit_credit', 55)->nullable(); // varchar(55) DEFAULT NULL
            $table->string('source', 25)->nullable(); // varchar(25) DEFAULT NULL
            $table->string('wallet_type', 100)->default('main_wallet'); // varchar(100) DEFAULT 'main_wallet'
            $table->double('ftm_amnt')->default(0); // double DEFAULT 0
            $table->decimal('amount', 10, 4)->default(0.0000); // decimal(10,4) NOT NULL DEFAULT 0.0000
            $table->decimal('tx_charge', 10, 2)->default(0.00); // decimal(10,2) NOT NULL DEFAULT 0.00
            $table->string('cripto_type', 255)->nullable(); // varchar(255) DEFAULT NULL
            $table->string('cripto_address', 255)->nullable(); // varchar(255) DEFAULT NULL
            $table->string('payment_type', 50)->nullable(); // varchar(50) DEFAULT NULL
            $table->mediumText('payment_slip'); // mediumtext NOT NULL (Laravel's mediumText is non-nullable by default)
            $table->dateTime('date')->useCurrent(); // datetime NOT NULL DEFAULT current_timestamp()
            $table->tinyInteger('status')->default(1); // tinyint(4) NOT NULL DEFAULT 1
            $table->tinyInteger('tds_status')->default(0)->nullable(); // tinyint(4) DEFAULT 0
            $table->mediumText('txs_res')->nullable(); // mediumtext DEFAULT NULL
            $table->string('txs_status', 200)->nullable(); // varchar(200) DEFAULT NULL
            $table->mediumText('bank_details')->nullable(); // mediumtext DEFAULT NULL
            $table->string('pan_number', 55)->nullable(); // varchar(55) DEFAULT NULL
            $table->double('open_wallet')->nullable(); // double DEFAULT NULL
            $table->double('closing_wallet')->nullable(); // double DEFAULT NULL
            $table->mediumText('remark')->nullable(); // mediumtext DEFAULT NULL
            $table->double('distribute_per')->nullable(); // double DEFAULT NULL
            $table->double('user_prsnt')->nullable(); // double DEFAULT NULL
            $table->mediumText('api_response')->nullable(); // mediumtext DEFAULT NULL
            $table->mediumText('tx_record')->nullable(); // mediumtext DEFAULT NULL
            $table->double('request_amount')->default(0); // double NOT NULL DEFAULT 0
            $table->double('paidAmount')->default(0); // double NOT NULL DEFAULT 0
            $table->string('cryp_status', 50)->nullable(); // varchar(50) DEFAULT NULL
            $table->string('cryp_paymentId', 255)->nullable(); // varchar(255) DEFAULT NULL
            $table->double('cryp_paymentAmount')->nullable(); // double DEFAULT NULL
            $table->string('cryp_paymentWallet', 255)->nullable(); // varchar(255) DEFAULT NULL
            $table->timestamp('cryp_expiryDate')->nullable(); // timestamp NULL DEFAULT NULL
            $table->text('approveData')->nullable(); // text DEFAULT NULL
            $table->text('tx_hash')->nullable(); // text DEFAULT NULL
            $table->mediumText('reason')->nullable(); // mediumtext DEFAULT NULL
            $table->integer('payout_id')->nullable(); // int(11) DEFAULT NULL
            $table->integer('payment_id')->nullable(); // int(11) DEFAULT NULL
            $table->tinyInteger('payout_status')->default(0); // tinyint(4) NOT NULL DEFAULT 0
            $table->tinyInteger('deposit_status')->default(0); // int(1) NOT NULL DEFAULT 0 (using tinyInteger for int(1))
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
        Schema::dropIfExists('transaction');
    }
}
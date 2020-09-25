<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('refund_transaction_id')->default(-1);
            $table->bigInteger('package_id');
            $table->bigInteger('shop_id');
            $table->bigInteger('shop_service_id');
            $table->bigInteger('amount')->default(0);
            $table->bigInteger('from_account_id');
            $table->bigInteger('to_account_id');
            $table->tinyInteger('type')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->string('reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}

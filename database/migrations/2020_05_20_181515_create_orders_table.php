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
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('payment_method_id');
            $table->enum('status', ['open', 'sent', 'finished', 'canceled'])->default('open');
            $table->boolean('is_ifood')->default(false);
            $table->boolean('is_with_vegetables')->default(true);
            $table->string('rebate')->nullable();
            $table->string('total_value');
            $table->dateTime('prevision');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign('orders_user_id_foreign');
            $table->dropForeign('orders_client_id_foreign');
            $table->dropForeign('orders_payment_method_id_foreign');
        });
        Schema::dropIfExists('orders');
    }
}

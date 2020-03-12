<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->bigInteger('shop_id')->unsigned();
            $table->string('name');
            $table->string('domain_url');
            $table->string('myshopify_domain');
            $table->string('store_name');
            $table->string('token');
            $table->boolean('token_valid');
            $table->boolean('products_synced')->default(false);
            $table->timestamps();

            $table->unique(['shop_id', 'name'], 'shop_identify');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shops');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeliveryContent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('status',function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
        });

        Schema::table('status',function(Blueprint $table){
            $sql="INSERT INTO status(name) VALUES('Draft'),('Live'),('Expired');";
            DB::connection()->getPdo()->exec($sql);
        });
        

        Schema::create('delivery_contents',function(Blueprint $table){
            $table->increments('id');
            $table->integer('delivery_id')->unsigned();
            $table->string('name');
            $table->integer('status_id')->unsigned();
            $table->unsignedSmallInteger('distribution')->default(100);
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('delivery_id')->references('id')->on('deliveries')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('status');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('delivery_contents');
        Schema::dropIfExists('status');
    }
}

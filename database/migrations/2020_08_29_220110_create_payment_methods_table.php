<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        /*Schema::create('state_payments', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('slug');
            $table->timestamps();
        });*/

        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('slug');
            $table->string('estado');
            $table->timestamps();
        });

        Schema::create('my_payment_methods', function (Blueprint $table) {
            $table->id();
            $table->boolean('status');
            $table->string('name')->nullable();
            $table->string('card_number')->nullable();
            $table->string('number_security')->nullable();
            $table->string('expiration_date')->nullable();
            $table->string('token')->nullable();
            $table->unsignedBigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('payment_methods_id')->unsigned();
            $table->foreign('payment_methods_id')->references('id')->on('payment_methods');
            $table->timestamps();
        });


           DB::table('payment_methods')->insert([
        [
            'slug'        => 'efectivo', 
            'nombre'      => 'Pago en efectivo',
            'estado'      => 'activo',
            'created_at'  =>  new DateTime, 
            'updated_at'  =>  new DateTime
        ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_methods'); 
    }
}

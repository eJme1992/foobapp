<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Pedidos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

       Schema::create('tipo_de_repartidor', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->boolean('estado');
            $table->string('slug');
            $table->timestamps();
        });

        Schema::create('estado_del_repartidor', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('slug');
            $table->timestamps();
        });

        Schema::create('repartidor', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion');
            $table->string('slug');
            $table->string('empresa');

            $table->unsignedBigInteger('tipo')->unsigned()
                  ->index()
                  ->nullable();
            $table->unsignedBigInteger('estado')->unsigned()
                  ->index()
                  ->nullable();
            $table->unsignedBigInteger('id_usuario')->unsigned()
                  ->index()
                  ->nullable();

            $table->foreign('id_usuario')
                  ->references('id')
                  ->on('users');

            $table->foreign('tipo')
                  ->references('id')
                  ->on('tipo_de_repartidor');

            $table->foreign('estado')
                  ->references('id')
                  ->on('estado_del_repartidor');

            $table->timestamps();
        });

       Schema::create('tipo_de_encargo', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->boolean('estado');
            $table->string('slug');
            $table->timestamps();
        });

        Schema::create('estado_del_encargo', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('slug');
            $table->timestamps();
        });

     
        Schema::create('encargo', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('calificacion_cocinero');
            $table->string('referencia_de_pago');
            $table->string('fecha');
            $table->string('hora');
            $table->string('subtotal');     // Suma de todo los platos
            $table->string('envio');        // Se suma Costo de envio 
            $table->string('impuestos');    // Se suma Impuestos 
            $table->string('descuentos');   // Se suma tabla de descuento Descuentos 
            $table->string('total');


            $table->unsignedBigInteger('tipo')->unsigned()         //tipo de pago
                  ->index()
                  ->nullable();
            $table->unsignedBigInteger('estado')->unsigned()       //estado_del_pago
                  ->index()
                  ->nullable();
            $table->unsignedBigInteger('tipo_de_pago')->unsigned() //estado_del_pago
                  ->index()
                  ->nullable();
            $table->unsignedBigInteger('id_usuario')->unsigned()
                  ->index()
                  ->nullable();
            $table->unsignedBigInteger('cocinero_id')->unsigned()
                  ->index()
                  ->nullable();
            $table->unsignedBigInteger('direcon_de_entrega')->unsigned()
                  ->index()
                  ->nullable();
            $table->unsignedBigInteger('direccion_de_busqueda')->unsigned()
                  ->index()
                  ->nullable();
            $table->unsignedBigInteger('medio_de_pago')->unsigned()
                  ->index()
                  ->nullable();

            $table->foreign('id_usuario')
                  ->references('id')
                  ->on('users');

            $table->foreign('tipo')
                  ->references('id')
                  ->on('tipo_de_encargo');

            $table->foreign('estado')
                  ->references('id')
                  ->on('estado_del_encargo');


              $table->foreign('tipo_de_pago')
                  ->references('id')
                  ->on('payment_methods');

            $table->foreign('cocinero_id')
                  ->references('id')
                  ->on('cocineros');

            $table->foreign('direcon_de_entrega')
                  ->references('id')
                  ->on('adresses');


              $table->foreign('direccion_de_busqueda')
                  ->references('id')
                  ->on('adresses');

              $table->foreign('medio_de_pago')
                  ->references('id')
                  ->on('my_payment_methods');

            $table->timestamps();
        });

     

        Schema::create('repartido_encargo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('repartidor')->unsigned() //tipo de pago
                  ->index()
                  ->nullable();
            $table->unsignedBigInteger('encargo')->unsigned() //estado_del_pago
                  ->index()
                  ->nullable();
             $table->foreign('repartidor')
                  ->references('id')
                  ->on('repartidor');
            $table->foreign('encargo')
                  ->references('id')
                  ->on('encargo');
            $table->timestamps();
        });

        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
           
            $table->integer('cantidad');            // Se suma tabla de descuento Descuentos 
           
            $table->string('calificacion_plato');

            $table->unsignedBigInteger('platos')->unsigned() //tipo de pago
                  ->index()
                  ->nullable();
            $table->unsignedBigInteger('encargo')->unsigned() //estado_del_pago
                  ->index()
                  ->nullable();
             $table->foreign('platos')
                  ->references('id')
                  ->on('platos');
            $table->foreign('encargo')
                  ->references('id')
                  ->on('encargo');
            $table->timestamps();
        });


    // ACA VA PLATO 





































    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

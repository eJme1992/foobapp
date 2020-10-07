<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Platos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

         Schema::create('estado_del_plato', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('slug');
            $table->timestamps();
          });

         Schema::create('platos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('descripcion');
            $table->string('slug');
            $table->string('precio');
            $table->string('porcion');

            $table->unsignedBigInteger('id_cocinero')->unsigned()
                  ->index()
                  ->nullable();

            $table->unsignedBigInteger('estado')->unsigned()
                  ->index()
                  ->nullable();

             $table->foreign('id_cocinero')
                  ->references('id')
                  ->on('cocineros');

            $table->foreign('estado')
                  ->references('id')
                  ->on('estado_del_plato');     
            //$table->boolean('estado');
            $table->timestamps();
        });

        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('slug');
            $table->timestamps();
        });

        Schema::create('ingredientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('slug');
            $table->timestamps();
        });

         
         Schema::create('platos_categoria', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_plato')->unsigned()
                  ->index()
                  ->nullable();

            $table->unsignedBigInteger('id_categoria')->unsigned()
                  ->index()
                  ->nullable();

             $table->foreign('id_plato')
                  ->references('id')
                  ->on('platos');

            $table->foreign('id_categoria')
                  ->references('id')
                  ->on('categorias');     
            //$table->boolean('estado');
            $table->timestamps();
         });


        Schema::create('platos_ingredientes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_plato')->unsigned()
                  ->index()
                  ->nullable();

            $table->unsignedBigInteger('id_ingrediente')->unsigned()
                  ->index()
                  ->nullable();

             $table->foreign('id_plato')
                  ->references('id')
                  ->on('platos');

            $table->foreign('id_ingrediente')
                  ->references('id')
                  ->on('ingredientes');     
            //$table->boolean('estado');
            $table->timestamps();
         });

         DB::table('estado_del_cocinero')->insert([
        [
            'slug'        => 'activo', 
            'nombre'      => 'Activo',
            'created_at'  =>  new DateTime, 
            'updated_at'  =>  new DateTime
        ],
        [
            'slug'        => 'inactivo', 
            'nombre'      => 'Inactivo',
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
        //
    }
}

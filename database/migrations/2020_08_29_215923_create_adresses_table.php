<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('state_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('descripcion');
            $table->timestamps();
        });

        Schema::create('type_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('descripcion');
            $table->timestamps();
        });

        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('iso');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('countries_id')->unsigned()
            ->index()
            ->nullable();
             $table->string('name');
             $table->string('fk_bacs');
             $table->integer('default_zip_code_bacs');
             $table->integer('default_cities_bacs');
             $table->timestamps();

             $table->foreign('countries_id')
                ->references('id')
                ->on('countries');
        });

        Schema::create('cities', function (Blueprint $table) {
             $table->id();
             $table->unsignedBigInteger('provinces_id')->unsigned()
            ->index()
            ->nullable();
             $table->string('name');
             $table->string('fk_bacs');
             $table->integer('order');
             $table->integer('zip_code');
             $table->timestamps();

             $table->foreign('provinces_id')
                ->references('id')
                ->on('provinces');
        });

        Schema::create('adresses', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_usuario')->unsigned()
            ->index()
            ->nullable();

            $table->unsignedBigInteger('pais')->unsigned()
            ->index()
            ->nullable();
            $table->unsignedBigInteger('provincia')->unsigned()
            ->index()
            ->nullable();
            $table->unsignedBigInteger('ciudad')->unsigned()
            ->index()
            ->nullable();
           
            $table->string('calle');
            $table->string('numero');
            $table->string('departamento');

            $table->string('codigo_postal');

            $table->unsignedBigInteger('estado')->unsigned()
            ->index()
            ->nullable();

            $table->unsignedBigInteger('tipo')->unsigned()
            ->index()
            ->nullable();


           $table->foreign('pais')
                ->references('id')
                ->on('countries');

            $table->foreign('provincia')
                ->references('id')
                ->on('provinces');

            $table->foreign('ciudad')
                ->references('id')
                ->on('cities');




            $table->foreign('estado')
                ->references('id')
                ->on('state_addresses');

            $table->foreign('tipo')
                ->references('id')
                ->on('type_addresses');

            $table->foreign('id_usuario')
                ->references('id')
                ->on('users');


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
        Schema::dropIfExists('state_addresses');
        Schema::dropIfExists('type_addresses');
        Schema::dropIfExists('adresses');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('provinces');
        Schema::dropIfExists('countries');
    }
}

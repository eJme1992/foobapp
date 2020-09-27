<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('estado_de_usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('descripcion');
            $table->timestamps();
        });

        Schema::create('tipo_de_usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('descripcion');
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            //$table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            $table->string('nombre');
            $table->string('apellido');
            $table->string('numero');

            $table->string('documento')->nullable();

            $table->string('slug')->unique();

            $table->unsignedBigInteger('estado')->unsigned()
            ->index()
            ->nullable();

            $table->unsignedBigInteger('tipo')->unsigned()
            ->index()
            ->nullable();

            $table->rememberToken();
            $table->timestamps();

            $table->foreign('estado')
                ->references('id')
                ->on('estado_de_usuarios');

            $table->foreign('tipo')
                ->references('id')
                ->on('tipo_de_usuarios');


        });

        DB::table('estado_de_usuarios')->insert([
        [
            'slug'        => 'activo', 
            'descripcion' => 'Activo',
            'created_at'  =>  new DateTime, 
            'updated_at'  =>  new DateTime
        ],
        [
            'slug'        => 'inactivo', 
            'descripcion' => 'Inactivo',
            'created_at'  =>  new DateTime, 
            'updated_at'  =>  new DateTime
        ],
        ]);

          DB::table('tipo_de_usuarios')->insert([
        [
            'slug'        => 'cocinero', 
            'descripcion' => 'Cocinero',
            'created_at'  =>  new DateTime, 
            'updated_at'  =>  new DateTime
        ],
        [
            'slug'        => 'consumidor', 
            'descripcion' => 'Consumidor',
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
        Schema::dropIfExists('users');
        Schema::dropIfExists('estado_de_usuarios');
        Schema::dropIfExists('tipo_de_usuarios');
       


    }
}

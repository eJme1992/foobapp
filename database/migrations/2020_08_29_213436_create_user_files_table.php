<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('tipo_de_archivos', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('descripcion');
            $table->timestamps();
        });

        Schema::create('estado_de_archivos', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('descripcion');
            $table->timestamps();
        });

        Schema::create('user_files', function (Blueprint $table) {
            $table->id();
           
            $table->unsignedBigInteger('id_usuario')->unsigned()
            ->index()
            ->nullable();
           
            $table->string('slug')->unique();

            $table->string('nombre');

            $table->string('url');

            $table->unsignedBigInteger('estado')->unsigned()
            ->index()
            ->nullable();

            $table->unsignedBigInteger('tipo')->unsigned()
            ->index()
            ->nullable();
            //$table->string('tipo_archivo');
            $table->timestamps();

            $table->foreign('estado')
                ->references('id')
                ->on('estado_de_archivos');

            $table->foreign('tipo')
                ->references('id')
                ->on('tipo_de_archivos');

            $table->foreign('id_usuario')
                ->references('id')
                ->on('users');


        });



        DB::table('estado_de_archivos')->insert([
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

          DB::table('tipo_de_archivos')->insert([
        [
            'slug'        => 'avatar', 
            'descripcion' => 'Imagen de perfil',
            'created_at'  =>  new DateTime, 
            'updated_at'  =>  new DateTime
        ],
        [
            'slug'        => 'dni', 
            'descripcion' => 'D.N.I',
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
        Schema::dropIfExists('user_files');
        Schema::dropIfExists('tipo_de_archivos');
        Schema::dropIfExists('estado_de_archivos');
    }
}

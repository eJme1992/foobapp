<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Cocinero extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('tipo_de_cocinero', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('descripcion');
            $table->string('slug');
            $table->boolean('estado');
            $table->timestamps();
        });

        Schema::create('estado_del_cocinero', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('slug');
            $table->timestamps();
        });


        Schema::create('cocineros', function (Blueprint $table) {
            $table->id();

            $table->string('alias');

            $table->string('slug');

            $table->text('descripcion')->nullable();
;
            
            $table->unsignedBigInteger('id_usuario')->unsigned()
                  ->index()
                  ->nullable();

            $table->unsignedBigInteger('estado')->unsigned()
                  ->index()
                  ->nullable();

            $table->unsignedBigInteger('tipo')->unsigned()
                  ->index()
                  ->nullable();

            $table->foreign('id_usuario')
                  ->references('id')
                  ->on('users');

            $table->foreign('estado')
                  ->references('id')
                  ->on('tipo_de_cocinero');

            $table->foreign('tipo')
                  ->references('id')
                  ->on('estado_del_cocinero');

            $table->timestamps();
        });

        Schema::create('dias_de_semana', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
        });

        Schema::create('horario_laboral', function (Blueprint $table) {
            $table->id();
            $table->string('dia');
            $table->string('hora_desde');
            $table->string('hora_hasta');
            $table->unsignedBigInteger('id_cocinero')->unsigned()
                  ->index()
                  ->nullable();

            $table->foreign('id_cocinero')
                  ->references('id')
                  ->on('cocineros');
            $table->foreign('dia')
                  ->references('id')
                  ->on('dias_de_semana');

            $table->timestamps();
        });

        DB::table('dias_de_semana')->insert([
        [         
            'nombre'      => 'Lunes',
        ],
        [         
            'nombre'      => 'Martes',
        ],
           [         
            'nombre'      => 'Miercoles',
        ],
           [         
            'nombre'      => 'Jueves',
        ],
           [         
            'nombre'      => 'Viernes',
        ],
           [         
            'nombre'      => 'Sabado',
        ],
           [         
            'nombre'      => 'Domingo',
        ],
        ]);


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

          DB::table('tipo_de_cocinero')->insert([
        [
            'slug'        => 'cocinero', 
            'nombre'      => 'Cocinero',
            'descripcion' => 'Persona natural',
            'estado'      =>  1,
            'created_at'  =>  new DateTime, 
            'updated_at'  =>  new DateTime
        ],
        [
            'slug'        => 'restaurante', 
            'nombre'      => 'Restaurante',
            'descripcion' => 'Persona Juridica',
            'estado'      =>  1,
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

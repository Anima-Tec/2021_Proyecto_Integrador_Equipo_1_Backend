<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->enum('type_report', ['opinión', 'denuncia']);
            $table->string('description');
            $table->integer('assessment')->default(1);
            $table->string('photo')->nullable();
            $table->integer('num_reports')->default(0);
            $table->tinyInteger('active')->default(1);
            $table->unsignedBigInteger('id_place');
            $table->foreign('id_place')->references('id')->on('places');
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
        Schema::dropIfExists('reports');
    }
}

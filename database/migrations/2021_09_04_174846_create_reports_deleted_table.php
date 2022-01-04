<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsDeletedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports_deleted', function (Blueprint $table) {
            $table->foreignId('id_person')->constrained('persons');
            $table->foreignId('id_report')->constrained('reports');
            $table->foreignId('id_place')->constrained('places');
            $table->foreignId('id_admin')->constrained('admins');
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
        Schema::dropIfExists('reports_deleted');
    }
}

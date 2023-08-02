<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project', function (Blueprint $table) {
            $table->id();
            $table->integer('company');
            $table->integer('contact');
            $table->string('name');
            $table->string('description');
            $table->string('image');
            $table->string('layout');
            $table->string('indoor');
            $table->string('ex1');
            $table->string('ex2');
            $table->integer('airflow');
            $table->integer('pressure');
            $table->integer('Tfin');
            $table->integer('Trin');
            $table->integer('Hfin');
            $table->integer('Hrin');
            $table->integer('modelId');
            $table->integer('status')->nullable(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project');
    }
}

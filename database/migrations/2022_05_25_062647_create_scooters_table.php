<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScootersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scooters', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->nullable();

            $table->string('last_name')->nullable();

            $table->string('phone')->nullable();

            $table->string('barcode')->nullable()->unique();

            $table->string('model')->nullable();
            
            $table->string('termen')->nullable();
            
            $table->string('signature_name')->nullable();
            
            $table->string('signature_file_path')->nullable();

            $table->longText('problem')->nullable();

            $table->longText('solved')->nullable();

            $table->string('price')->nullable();

            $table->unsignedInteger('status_id')->nullable();

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
        Schema::dropIfExists('scooters');
    }
}

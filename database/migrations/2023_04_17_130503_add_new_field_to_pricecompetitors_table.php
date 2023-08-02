<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldToPricecompetitorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pricecompetitors', function (Blueprint $table) {
            //
            $table->string('website')->nullable()->after('name');
            $table->string('attachment')->nullable()->after('website');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pricecompetitors', function (Blueprint $table) {
            //
            $table->dropColumn('website');
            $table->dropColumn('attachment');
        });
    }
}

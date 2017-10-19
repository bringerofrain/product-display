<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTrackerSoftDeleteToProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('track_click')->after('scheduled')->unsigned()->default(0);
            $table->integer('track_organic')->after('track_click')->unsigned()->default(0);
            $table->boolean('active')->after('track_organic')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('track_click');
            $table->dropColumn('track_organic');
            $table->dropColumn('active');
        });
    }
}

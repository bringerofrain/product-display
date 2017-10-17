<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTracklogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracklog', function (Blueprint $table) {
            $table->increments('id');
            $table->string('session_id');
            $table->string('controller',25);
            $table->string('method',25);
            $table->string('url');
            $table->string('from_url');
            $table->text('client');
            $table->boolean('mobile');
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
        Schema::dropIfExists('tracklog');
    }
}

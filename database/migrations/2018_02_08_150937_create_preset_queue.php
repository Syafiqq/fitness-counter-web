<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePresetQueue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('preset_queue', function (Blueprint $table) {
            $table->string('preset', 20);
            $table->string('participant', 20);
            $table->timestamp('created_at')->useCurrent();
            $table->primary(['preset', 'participant']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('preset_queue');
    }
}

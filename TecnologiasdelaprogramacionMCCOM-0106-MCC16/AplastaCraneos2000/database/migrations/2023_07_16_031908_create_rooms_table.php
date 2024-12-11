<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name')->nullable()->default('NULL');
            $table->string('player_turn')->nullable()->default('NULL');
            $table->integer('host_id')->nullable();
            $table->integer('guest_id')->nullable();
            $table->integer('result')->nullable();
            $table->string('fen')->default('');
            $table->string('pass')->nullable()->default('NULL');
            $table->timestamp('modified_at');
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
        Schema::dropIfExists('rooms');
    }
};

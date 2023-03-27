<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTodosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('todos', function (Blueprint $table) {
            $table->uuid('id');

            $table->foreignId('user_id')
                    ->constrained()
                    ->onUpdate('cascade')
                    ->onUpdate('cascade');

            $table->string('task', 255);

            $table->longText('description')->nullable();

            $table->unsignedBigInteger('created_at');

            $table->unsignedBigInteger('updated_at')->nullable();

            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('todos');
    }
}

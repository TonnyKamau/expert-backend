<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('userid');
            $table->string('taskid');
            $table->string('title');
            $table->string('mode');
            $table->string('type');
            $table->string('level');
            $table->string('duration');
            $table->integer('page');
            $table->string('format');
            $table->integer('price');
            $table->string('attachment')->nullable();
            $table->string('addtionalinfo')->nullable();
            $table->string('assignedby')->nullable();
            $table->string('state');
            $table->string('upload')->nullable();
            $table->string('writer')->nullable();
            $table->string('writingtips')->nullable();
            $table->string('rate')->nullable();
            $table->string('comment')->nullable();
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
        Schema::dropIfExists('tasks');
    }
}

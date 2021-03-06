<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('group_message', function(Blueprint $table) {
        $table->integer('message_id')->unsigned()->index();
        $table->integer('group_id')->unsigned()->index();

        $table->foreign('message_id')->references('id')->on('messages')->onDelete('cascade');
        $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
        $table->primary(['message_id', 'group_id']);
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::drop('group_message');
    }
}

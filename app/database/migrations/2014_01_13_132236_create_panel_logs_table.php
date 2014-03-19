<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePanelLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('panel_logs', function(Blueprint $table)
		{
			$table->increments('id');
			
			// We'll need to ensure that MySQL uses the InnoDB engine to
			// support the indexes, other engines aren't affected.
			$table->engine = 'InnoDB';
			
			// ->nullable() on these foreign keys is ok because the relation is one to many
			$table->integer('account_id')->unsigned()->nullable();
			$table->integer('user_id')->unsigned()->nullable();
			$table->integer('group_id')->unsigned()->nullable();
			
			$table->binary('account_object')->nullable();
			$table->binary('user_object')->nullable();
			$table->binary('group_object')->nullable();
			
			$table->integer('event')->unsigned()->nullable();
			$table->text('description')->nullable();
			$table->string('type')->nullable();
			
			$table->index('account_id');
			$table->index('user_id');
			$table->index('group_id');
			
			$table->foreign('account_id')->references('id')->on('accounts')->onDelete('set null');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
			$table->foreign('group_id')->references('id')->on('groups')->onDelete('set null');
			
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
		Schema::drop('panel_logs');
	}

}
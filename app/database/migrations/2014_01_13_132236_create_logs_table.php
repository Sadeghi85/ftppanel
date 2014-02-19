<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('logs', function(Blueprint $table)
		{
			$table->increments('id');
			
			// We'll need to ensure that MySQL uses the InnoDB engine to
			// support the indexes, other engines aren't affected.
			$table->engine = 'InnoDB';
			
			// ->nullable() on these foreign keys is ok because the relation is one to many
			$table->integer('account_id')->unsigned()->nullable();
			$table->integer('user_id')->unsigned()->nullable();
			
			$table->string('event')->nullable();
			$table->text('description');
			$table->string('type')->nullable();
			
			$table->index('account_id');
			$table->index('user_id');
			
			$table->foreign('account_id')->references('id')->on('accounts')->onDelete('set null');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
			
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
		Schema::drop('logs');
	}

}
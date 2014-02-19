<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//Schema::table('account_user', function(Blueprint $table)
		Schema::create('account_user', function(Blueprint $table)
		{
			//$table->increments('id');
			
			$table->integer('account_id')->unsigned();
			$table->integer('user_id')->unsigned();
			
			// We'll need to ensure that MySQL uses the InnoDB engine to
			// support the indexes, other engines aren't affected.
			$table->engine = 'InnoDB';
			$table->primary(array('account_id', 'user_id'));
			
			$table->index('account_id');
			$table->index('user_id');
			
			$table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			
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
		// Schema::table('account_user', function(Blueprint $table)
		// {
			
		// });
		
		Schema::drop('account_user');
	}

}
<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//Schema::table('accounts', function(Blueprint $table)
		Schema::create('accounts', function(Blueprint $table)
		{
			$table->increments('id');

			$table->boolean('activated')->default(0);

			$table->string('username')->unique();
			$table->string('password');
			$table->string('home');
			//$table->text('comment')->default(''); # MySQL strict mode: BLOB/TEXT column 'comment' can't have a default value
			$table->text('comment');

			$table->smallInteger('ulbandwidth')->unsigned()->default(0);
			$table->smallInteger('dlbandwidth')->unsigned()->default(0);
			$table->smallInteger('quotasize')->unsigned()->default(0);
			$table->integer('quotafiles')->unsigned()->default(0);

			// We'll need to ensure that MySQL uses the InnoDB engine to
			// support the indexes, other engines aren't affected.
			$table->engine = 'InnoDB';
			
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
		// Schema::table('accounts', function(Blueprint $table)
		// {
			
		// });
		
		Schema::drop('accounts');
	}

}
<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAliasesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
//		Schema::table('aliases', function(Blueprint $table)
		Schema::create('aliases', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('account_id')->unsigned();

			$table->string('domain')->default(Config::get('ftppanel.ftpDefaultDomain'));

			// We'll need to ensure that MySQL uses the InnoDB engine to
			// support the indexes, other engines aren't affected.
			$table->engine = 'InnoDB';

			$table->index('account_id');

			$table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');

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
//		Schema::table('aliases', function(Blueprint $table)
//		{
//			//
//		});
		Schema::drop('aliases');
	}

}
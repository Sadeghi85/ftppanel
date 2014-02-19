<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIpTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
//		Schema::table('ip', function(Blueprint $table)
		Schema::create('ip', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('account_id')->unsigned();

			$table->integer('ip_start')->unsigned()->default(0);
			$table->integer('ip_end')->unsigned()->default(4294967295);

			$table->string('ip_start_for_humans')->default('0.0.0.0');
			$table->string('ip_end_for_humans')->default('255.255.255.255');

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
//		Schema::table('ip', function(Blueprint $table)
//		{
//			//
//		});
		Schema::drop('ip');
	}

}
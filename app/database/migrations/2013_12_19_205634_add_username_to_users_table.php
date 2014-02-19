<?php

use Illuminate\Database\Migrations\Migration;

class AddUsernameToUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function($table)
        {
			$table->string('username')->after('email');
			$table->dropUnique('users_email_unique');
			$table->unique('username');
			
            DB::statement('ALTER TABLE `users` MODIFY `email` VARCHAR(255) DEFAULT NULL;');

        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function($table)
        {
			//$table->dropUnique('users_username_unique');
			//$table->dropColumn('username');
			//$table->unique('email');
            
            DB::statement('ALTER TABLE `users` MODIFY `email` VARCHAR(255) NOT NULL;');
 
        });
	}

}
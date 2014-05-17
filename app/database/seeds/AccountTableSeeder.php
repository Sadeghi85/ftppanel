<?php

class AccountTableSeeder extends Seeder {

    public function run()
    {
        DB::table('accounts')->delete();
		
		$account = new Account;
		
		$account->activated = 1;
	    $account->username = 'sadeghi85';
	    $account->password = sha1('123456');
	    $account->home = Config::get('ftppanel.ftpHome').'/sadeghi85';
		$account->comment = '';
		
		$account->save();
    }
}
<?php

class IpTableSeeder extends Seeder {

    public function run()
    {
        DB::table('ip')->delete();
		
		$ip = new Ip;
		
		$ip->account_id = 1;
		
		$ip->save();
    }
}
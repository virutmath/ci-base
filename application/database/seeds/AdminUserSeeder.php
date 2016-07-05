<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 6/14/2016
 * Time: 10:36 AM
 */
class AdminUserSeeder extends Seeder
{
    public function run()
    {
	    $this->db->truncate('admin_users');
	    $this->db->truncate('admin_group');
	    $faker = [
		    [/*loginname*/'admin',/*group id*/ 1
			    ,/*password*/ '123456',/*hash*/ '11223'
			    , /*Name*/'Admin', /*Email*/'admin@localhost.com'
			    , /*is admin*/1, /*Active*/1]

		    ,[/*loginname*/'cskh',/*group id*/ 1
			    ,/*password*/ '123456',/*hash*/ '11223'
			    , /*Name*/'CSKH', /*Email*/'cskh@localhost.com'
			    , /*is admin*/0, /*Active*/1]
	    ];
	    foreach ($faker as $fake) {
		    $data = [
			    'loginname' => $fake[0]
			    ,'group_id'=>$fake[1]
			    ,'password'=>password_hash($fake[2] . $fake[3],PASSWORD_DEFAULT)
			    ,'hash'=>$fake[3]
			    ,'name'=>$fake[4]
			    ,'email'=>$fake[5]
			    ,'is_admin'=>$fake[6]
			    ,'active'=>$fake[7]
			    ,'created_at' => time()
			    ,'updated_at' => time()
		    ];
		    $this->db->insert('admin_users', $data);
	    }
	    $faker = [
		    ['admin']
	    ];
	    foreach ($faker as $fake) {
		    $data = [
			    'name'=>$fake[0]
		    ];
		    $this->db->insert('admin_group',$data);
	    }
    }
}
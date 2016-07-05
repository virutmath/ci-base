<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 6/13/2016
 * Time: 5:32 PM
 */
class Admin_group_role_model extends MY_Model
{
    public $table = 'admin_group_role';
    public $primary_key = 'id';
    public $timestamps = FALSE;
    public function __construct()
    {
	    $this->has_one['module'] = [
		    'foreign_model'=>'Module_model',
		    'foreign_table'=>'modules',
		    'foreign_key'=>'id',
		    'local_key'=>'module_id'
	    ];
        parent::__construct();
    }
}
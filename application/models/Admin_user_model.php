<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 6/13/2016
 * Time: 5:24 PM
 */
class Admin_user_model extends MY_Model
{
    public $table = 'admin_users';
    public $primary_key = 'id';
    public $soft_deletes = TRUE;
    public $timestamps_format = 'timestamp';
    
    public function __construct()
    {
        parent::__construct();
        $this->has_one['adminGroup'] = [
            'foreign_model'=>'Admin_group_model',
            'foreign_table'=>'admin_group',
            'foreign_key'=>'id',
            'local_key'=>'group_id'
        ];
    }
}
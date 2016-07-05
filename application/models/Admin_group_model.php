<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 6/13/2016
 * Time: 5:33 PM
 */
class Admin_group_model extends MY_Model
{
    public $table = 'admin_group';
    public $primary_key = 'id';
    public $timestamps = FALSE;
    
    public function __construct()
    {
        parent::__construct();
        
    }
}
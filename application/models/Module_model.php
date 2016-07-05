<?php

/**
 * Author KienDT.
 * Email: kiendt@i-com.vn
 * Auto-generate by CMS Command
 */
class Module_model extends MY_Model
{
	public $table = 'modules';
	public $primary_key = 'id';
	public $timestamps = FALSE;

	public function __construct()
	{
//		$this->has_one['blog'] = [
//			'foreign_model'=>'Blog_model',
//			'foreign_table'=>'blogs',
//			'foreign_key'=>'id',
//			'local_key'=>'blog_id'
//		];
//		$this->has_many['blog'] = [
//			'foreign_model'=>'Blog_model',
//			'foreign_table'=>'blogs',
//			'foreign_key'=>'id',
//			'local_key'=>'blog_id'
//		];
		parent::__construct();
	}
}
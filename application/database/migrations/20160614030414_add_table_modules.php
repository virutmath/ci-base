<?php
/**
 * Migration: add_table_modules
 *
 * Created by: Cli for CodeIgniter <https://github.com/kenjis/codeigniter-cli>
 * Created on: 2016/06/14 03:04:14
 */
class Migration_add_table_modules extends CI_Migration {

	public function up()
	{
//		// Creating a table
//		$this->dbforge->add_field(array(
//			'blog_id' => array(
//				'type' => 'INT',
//				'constraint' => 11,
//				'auto_increment' => TRUE
//			),
//			'blog_title' => array(
//				'type' => 'VARCHAR',
//				'constraint' => 100,
//			),
//			'blog_author' => array(
//				'type' =>'VARCHAR',
//				'constraint' => '100',
//				'default' => 'King of Town',
//			),
//			'blog_description' => array(
//				'type' => 'TEXT',
//				'null' => TRUE,
//			),
//		));
//		$this->dbforge->add_key('blog_id', TRUE);
//		$this->dbforge->create_table('blog');
        $this->dbforge->add_field([
            'id'=>[
                'type'=>'INT',
                'auto_increment'=>TRUE
            ],
            'name'=>[
                'type'=>'VARCHAR',
                'constraint'=>100
            ],
            'controller'=>[
                'type'=>'VARCHAR',
                'constraint'=>255,
                'NULL'=>TRUE
            ],
            'method'=>[
                'type'=>'VARCHAR',
                'constraint'=>255,
                'NULL'=>TRUE
            ],
            'parent_id'=>[
                'type'=>'INT',
                'default'=>0
            ],
            'order'=>[
                'type'=>'INT',
                'default'=>0
            ],
            'active'=>[
                'type'=>'TINYINT',
                'default'=>1
            ]
        ]);
        $this->dbforge->add_key('id',TRUE);
        $this->dbforge->create_table('modules');
//		// Adding a Column to a Table
//		$fields = array(
//			'preferences' => array('type' => 'TEXT')
//		);
//		$this->dbforge->add_column('table_name', $fields);
	}

	public function down()
	{
//		// Dropping a table
//		$this->dbforge->drop_table('blog');
        $this->dbforge->drop_table('modules');
//		// Dropping a Column From a Table
//		$this->dbforge->drop_column('table_name', 'column_to_drop');
	}

}

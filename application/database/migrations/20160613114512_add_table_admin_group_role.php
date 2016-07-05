<?php
/**
 * Migration: add_table_admin_group_role
 *
 * Created by: Cli for CodeIgniter <https://github.com/kenjis/codeigniter-cli>
 * Created on: 2016/06/13 11:45:12
 */
class Migration_add_table_admin_group_role extends CI_Migration {

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
                'constraint'=>11,
                'auto_increment'=> TRUE
            ],
            'group_id'=>[
                'type'=>'INT'
            ],
            'module_id'=>[
                'type'=>'INT'
            ],
            'role_add'=>[
                'type'=>'TINYINT',
                'default'=>0
            ],
            'role_edit'=>[
                'type'=>'TINYINT',
                'default'=>0
            ],
            'role_delete'=>[
                'type'=>'TINYINT',
                'default'=>0
            ],
            'role_export'=>[
                'type'=>'TINYINT',
                'default'=>0
            ],
            'role_import'=>[
                'type'=>'TINYINT',
                'default'=>0
            ],
            'role_view'=>[
                'type'=>'TINYINT',
                'default'=>0
            ]
        ]);
        $this->dbforge->add_key('id',TRUE);
        $this->dbforge->create_table('admin_group_role');

//		// Adding a Column to a Table
//		$fields = array(
//			'preferences' => array('type' => 'TEXT')
//		);
//		$this->dbforge->add_column('table_name', $fields);
	}

	public function down()
	{
//		// Dropping a table
		$this->dbforge->drop_table('admin_group_role');

//		// Dropping a Column From a Table
//		$this->dbforge->drop_column('table_name', 'column_to_drop');
	}

}

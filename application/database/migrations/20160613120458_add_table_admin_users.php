<?php
/**
 * Migration: add_table_admin_users
 *
 * Created by: Cli for CodeIgniter <https://github.com/kenjis/codeigniter-cli>
 * Created on: 2016/06/13 12:04:58
 */
class Migration_add_table_admin_users extends CI_Migration {

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
                'auto_increment'=>TRUE
            ],
            'loginname'=>[
                'type'=>'VARCHAR',
                'constraint'=>100
            ],
            'group_id'=>[
                'type'=>'INT'
            ],
            'password'=>[
                'type'=>'VARCHAR',
                'constraint'=>100
            ],
            'hash'=>[
                'type'=>'VARCHAR',
                'constraint'=>255
            ],
            'name'=>[
                'type'=>'VARCHAR',
                'constraint'=>100
            ],
            'email'=>[
                'type'=>'VARCHAR',
                'constraint'=>100,
                'NULL'=>TRUE
            ],
            'address'=>[
                'type'=>'VARCHAR',
                'constraint'=>255,
                'NULL'=>TRUE
            ],
            'phone'=>[
                'type'=>'VARCHAR',
                'constraint'=>100,
                'NULL'=>TRUE
            ],
            'is_admin'=>[
                'type'=>'TINYINT',
                'default'=>0
            ],
            'active'=>[
                'type'=>'TINYINT',
                'default'=>1
            ],
            'created_at'=>[
                'type'=>'INT'
            ],
            'updated_at'=>[
                'type'=>'INT'
            ],
            'deleted_at'=>[
                'type'=>'INT',
                'NULL'=>TRUE
            ]
        ]);
        $this->dbforge->add_key('id',TRUE);
        $this->dbforge->create_table('admin_users');
//		// Adding a Column to a Table
//		$fields = array(
//			'preferences' => array('type' => 'TEXT')
//		);
//		$this->dbforge->add_column('table_name', $fields);
	}

	public function down()
	{
//		// Dropping a table
		$this->dbforge->drop_table('admin_users');

//		// Dropping a Column From a Table
//		$this->dbforge->drop_column('table_name', 'column_to_drop');
	}

}

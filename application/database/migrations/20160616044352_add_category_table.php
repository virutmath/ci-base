<?php

/**
 * Migration: add_category_table
 *
 * Created by: Cli for CodeIgniter <https://github.com/kenjis/codeigniter-cli>
 * Created on: 2016/06/16 04:43:52
 */
class Migration_add_category_table extends CI_Migration
{

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

//		// Adding a Column to a Table
//		$fields = array(
//			'preferences' => array('type' => 'TEXT')
//		);
//		$this->dbforge->add_column('table_name', $fields);
        // Creating a table
        $this->dbforge->add_field('id');
        $this->dbforge->add_field([
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ]
        ]);
        $this->dbforge->add_field([
            'parent_id' => [
                'type' => 'INT',
                'default' => 0
            ]
        ]);
        $this->dbforge->add_field([
            'active' => [
                'type' => 'TINYINT',
                'default' => 1
            ]
        ]);
        $this->dbforge->add_field([
            'title' => [
                'type' => 'VARCHAR',
                'NULL' => TRUE,
                'constraint' => 255
            ]
        ]);
        $this->dbforge->add_Field([
            'description' => [
                'type' => 'VARCHAR',
                'NULL' => TRUE,
                'constraint' => 255
            ]
        ]);
        $this->dbforge->add_field([
            'keyword' => [
                'type' => 'VARCHAR',
                'NULL' => TRUE,
                'constraint' => 255
            ]
        ]);
        $this->dbforge->add_field([
            'image' => [
                'type' => 'VARCHAR',
                'NULL' => TRUE,
                'constraint' => 255
            ]
        ]);
        $this->dbforge->add_field([
            'icon' => [
                'type' => 'INT',
                'default'=>0
            ],
            'has_child' => [
                'type' => 'INT',
                'default' => 0
            ],
            'created_at' => [
                'type' => 'INT',
                'NULL' => TRUE,
            ],
            'updated_at' => [
                'type' => 'INT',
                'NULL' => TRUE,
            ],
            'deleted_at' => [
                'type' => 'INT',
                'NULL' => TRUE,
            ]
        ]);
        $this->dbforge->add_key(['name','parent_id','active','title','has_child','icon']);
        $this->dbforge->create_table('categories');
    }

    public function down()
    {
//		// Dropping a table
//		$this->dbforge->drop_table('blog');
        $this->dbforge->drop_table('categories');
//		// Dropping a Column From a Table
//		$this->dbforge->drop_column('table_name', 'column_to_drop');
    }

}

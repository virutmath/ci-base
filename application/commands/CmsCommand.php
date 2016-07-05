<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 6/17/2016
 * Time: 9:50 AM
 */
class CmsCommand extends Command
{
	public function __invoke()
	{
		//load inflection
		$this->ci->load->helper('inflector');
		$this->ci->load->helper('string');
		$this->ci->load->helper('common');
		$argv = $this->context->argv->get();
		array_shift($argv);
		array_shift($argv);
		//argv
		$method = $argv[0];
		array_shift($argv);
		$params = $argv;
		switch ($method) {
			case 'create':
				call_user_func_array([$this, 'create'], $params);
				break;
		}

//		$this->stdio->outln(implode(' ', $argv));

	}

	private function log($message)
	{
		$this->stdio->outln($message);
	}

	protected function create()
	{
		$args = func_get_args();
		//table name
		$table = $args[0];
		if (!$table) {
			$this->log('Missing parameter : `php cli cms create <table_name>`');
			return;
		}
		//check table
		$query = $this->ci->db->query('SHOW TABLES LIKE "' . $table . '"');
		if (!$query->num_rows()) {
			$this->log('Error : Table ' . $table . ' not found');
			return;
		}
		//show columns
		$query = $this->ci->db->query('SHOW COLUMNS FROM ' . $table);
		$columns = [];
		foreach ($query->result_array() as $row) {
			$columns[] = $row['Field'];
		}

		$classname = camel_case(singular($table));
		//check variable
		array_shift($args);
		$option = $args;
		$this->createModel($classname);
		$this->createSolid($classname, $columns);
		if (is_array($option) && count($option) == 1) {
			if (in_array('--all', $option)) {
				$this->createController($classname, 'admin');
				$this->createController($classname, 'client');
				$this->createRoute($classname);
				$this->createAdminView($classname);
			}
			if (in_array('--admin', $option)) {
				$this->createController($classname, 'admin');
				$this->createRoute($classname);
				$this->createAdminView($classname);
			}
			if (in_array('--client', $option)) {
				$this->createController($classname, 'client');
			}
		} else {
			//generate all
			$this->createController($classname, 'admin');
			$this->createController($classname, 'client');
			$this->createAdminView($classname);
			$this->createRoute($classname);
		}

	}

	protected function createController($classname, $type = '')
	{
		//create folder
		$folder = snake_case($classname);
		$module = str_replace('_','-',$folder);
		$uc_type = ucfirst($type);
		$Classname = ucfirst($classname);
		$template = file_get_contents(__DIR__ . '/templates/' . $uc_type . 'Controller.txt');
		$search = [
			'@@classname@@',
			'@@Classname@@',
			'@@module@@',
			'@@folder@@',
			'@@date@@',
		];
		$replace = [
			$classname,
			$Classname,
			$module,
			$folder,
			date('Y/m/d H:i:s'),
		];
		$output = str_replace($search, $replace, $template);
		$file_path = APPPATH . 'controllers/' . $type . '/' . $Classname . 'Controller.php';
		$generated = @file_put_contents($file_path, $output, LOCK_EX);

		if ($generated !== false) {
			$this->stdio->outln('<<green>>Generated: ' . $file_path . '<<reset>>');
		} else {
			$this->stdio->errln(
				"<<red>>Can't write to \"$file_path\"<<reset>>"
			);
		}
	}

	protected function createModel($classname)
	{
		$Classname = ucfirst($classname);
		$table = plural(strtolower($classname));
		$template = file_get_contents(__DIR__ . '/templates/Cms_model.txt');
		$search = [
			'@@classname@@',
			'@@Classname@@',
			'@@table@@',
			'@@date@@',
		];
		$replace = [
			$classname,
			$Classname,
			$table,
			date('Y/m/d H:i:s'),
		];
		$output = str_replace($search, $replace, $template);
		$file_path = APPPATH . 'models/' . $Classname . '_model.php';
		//check model exist
		if (file_exists($file_path)) {
			$this->stdio->outln('<<green>>Can\'t overwrite. Model exist: ' . $file_path . '<<reset>>');
			return false;
		}
		$generated = @file_put_contents($file_path, $output, LOCK_EX);
		if ($generated !== false) {
			$this->stdio->outln('<<green>>Generated: ' . $file_path . '<<reset>>');
		} else {
			$this->stdio->errln(
				"<<red>>Can't write to \"$file_path\"<<reset>>"
			);
		}
	}

	protected function createSolid($classname, $columns)
	{
		$Classname = ucfirst($classname);
		//set column property for collection
		$listProperties = '$this->listProperties = [';
		$property = '';
		foreach ($columns as $field) {
			$property .= 'protected $' . camel_case($field) . ';' . PHP_EOL . "\t";
			$listProperties .= '"' . $field . '",';
		}
		$listProperties = rtrim($listProperties, ',') . '];';
		//create collection
		$template = file_get_contents(__DIR__ . '/templates/CmsCollection.txt');
		$search = [
			'@@classname@@',
			'@@Classname@@',
			'@@property@@',
			'@@listProperties@@',
		];
		$replace = [
			$classname,
			$Classname,
			$property,
			$listProperties,
		];
		$output = str_replace($search, $replace, $template);
		$file_path = APPPATH . 'Solid/Collections/' . $Classname . '.php';
		if (file_exists($file_path)) {
			$this->stdio->outln('<<green>>Can\'t overwrite. Collection exist: ' . $file_path . '<<reset>>');
			return false;
		}
		$generated = @file_put_contents($file_path, $output, LOCK_EX);
		if ($generated !== false) {
			$this->stdio->outln('<<green>>Generated: ' . $file_path . '<<reset>>');
		} else {
			$this->stdio->errln(
				"<<red>>Can't write to \"$file_path\"<<reset>>"
			);
		}

		//create Repository
		$template = file_get_contents(__DIR__ . '/templates/CmsRepository.txt');
		$search = [
			'@@classname@@',
			'@@Classname@@',
		];
		$replace = [
			$classname,
			$Classname,
		];
		$output = str_replace($search, $replace, $template);
		$file_path = APPPATH . 'Solid/Repositories/' . $Classname . 'Repository.php';
		if (file_exists($file_path)) {
			$this->stdio->outln('<<green>>Can\'t overwrite. Repository exist: ' . $file_path . '<<reset>>');
			return false;
		}
		$generated = @file_put_contents($file_path, $output, LOCK_EX);
		if ($generated !== false) {
			$this->stdio->outln('<<green>>Generated: ' . $file_path . '<<reset>>');
		} else {
			$this->stdio->errln(
				"<<red>>Can't write to \"$file_path\"<<reset>>"
			);
		}
	}

	protected function createRoute($classname)
	{
		//create folder
		$folder = snake_case($classname);
		$module = str_replace('_','-',$folder);
		$path_dir = APPPATH . 'config/routes/modules/' . $folder . '/';
		if (!file_exists($path_dir)) {
			$generated = @mkdir($path_dir, 0777, 1);
			if ($generated !== false) {
				$this->stdio->outln('<<green>>Generated folder: ' . $path_dir . '<<reset>>');
			} else {
				$this->stdio->errln(
					"<<red>>Can't write to \"$path_dir\"<<reset>>"
				);
			}
		}
		$Classname = ucfirst($classname);
		//create route
		$template = file_get_contents(__DIR__ . '/templates/CmsRoute.txt');
		$search = [
			'@@module@@',
			'@@folder@@',
			'@@Classname@@',
		];
		$replace = [
			$module,
			$folder,
			$Classname,
		];
		$output = str_replace($search, $replace, $template);
		$file_path = $path_dir . 'routes.php';
		if (file_exists($file_path)) {
			$this->stdio->outln('<<green>>Can\'t overwrite. Route exist: ' . $file_path . '<<reset>>');
			return false;
		}
		$generated = @file_put_contents($file_path, $output, LOCK_EX);
		if ($generated !== false) {
			$this->stdio->outln('<<green>>Generated: ' . $file_path . '<<reset>>');
		} else {
			$this->stdio->errln(
				"<<red>>Can't write to \"$file_path\"<<reset>>"
			);
		}
	}

	protected function createAdminView($classname)
	{
		//create folder
		$folder = snake_case($classname);
		$module = str_replace('_','-',$folder);
		$path_dir = APPPATH . 'views/admin/' . $folder . '/';
		if (!file_exists($path_dir)) {
			$generated = @mkdir($path_dir, 0777, 1);
			if ($generated !== false) {
				$this->stdio->outln('<<green>>Generated folder: ' . $path_dir . '<<reset>>');
			} else {
				$this->stdio->errln(
					"<<red>>Can't write to \"$path_dir\"<<reset>>"
				);
			}
		}
		//create view file
		foreach (['add','edit','index'] as $item) {
			$template = file_get_contents(__DIR__ . '/templates/views/admin/'.$item.'.txt');
			$search = [
				'@@module@@',
				'@@classname@@',
			];
			$replace = [
				$module,
				$classname,
			];
			$output = str_replace($search, $replace, $template);
			$file_path = $path_dir . $item.'.blade.php';
			if(file_exists($file_path)) {
				$this->stdio->outln('<<green>>Can\'t overwrite. View exist: ' . $file_path . '<<reset>>');
				return false;
			}
			$generated = @file_put_contents($file_path, $output, LOCK_EX);
			if ($generated !== false) {
				$this->stdio->outln('<<green>>Generated: ' . $file_path . '<<reset>>');
			} else {
				$this->stdio->errln(
					"<<red>>Can't write to \"$file_path\"<<reset>>"
				);
			}
		}
	}
}
<?php
use Solid\Repositories\AdminUserRepository;
use Solid\Repositories\AdminModuleRepository;
use Solid\Repositories\migrationRepository;
use Solid\Collections\migration;
/**
 * Created by ICOM-CMS
 * User: kiendt
 * From cms cli
 * Class MigrationController
 * @property MY_Model $migrationModel
 */
class MigrationController extends AdminController
{
	/**
	 * @var MigrationRepository $migrationRepository
	 */
	private $migrationRepository;
	public function __construct(AdminUserRepository $adminUserRepository,
						AdminModuleRepository $adminModuleRepository,
						MigrationRepository $migrationRepository)
	{
		parent::__construct($adminUserRepository, $adminModuleRepository);
		//load model
		$this->load->model('$Migration_model','migrationModel');

        //dependencies injection
		$this->migrationRepository = $migrationRepository;
		$this->migrationRepository->setModel($this->migrationModel);
	}

	public function before()
	{
		parent::before(); // TODO: Change the autogenerated stub
	}

	public function after()
	{
		parent::after(); // TODO: Change the autogenerated stub
	}

	public function index()
	{
		
	}

	public function add()
	{

	}

	public function postAdd()
	{

	}

	public function edit()
	{

	}

	public function postEdit()
	{

	}

	public function ajaxDelete()
	{

	}

	public function ajaxUpdate()
	{
		
	}
}
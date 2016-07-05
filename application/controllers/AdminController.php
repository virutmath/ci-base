<?php
use Solid\Repositories\AdminUserRepository as AdminUserRepository;
use Solid\Repositories\AdminModuleRepository as AdminModuleRepository;
use Solid\Builder\SidebarMenu as SidebarMenu;

/**
 * Class AdminController
 * @property CI_Loader $load
 * @property Admin_user_model $adminUserModel
 * @property Module_model $moduleModel
 * @property Admin_group_role_model $adminGroupRoleModel
 * @property Admin_group_model $adminGroupModel
 */
abstract class AdminController extends AuthController
{
	/**
	 * @var AdminUserRepository $adminUserRepository
	 */
	protected $adminUserRepository;

	protected $adminModule;

	/**
	 * @var AdminModuleRepository
	 */
	protected $adminModuleRepository;

	public $sidebarMenuData;


	public function __construct(AdminUserRepository $adminUserRepository, AdminModuleRepository $adminModuleRepository)
	{
		parent::__construct();
		//load model
		$this->load->model('admin_user_model', 'adminUserModel');
		$this->load->model('Module_model', 'moduleModel');
		$this->load->model('Admin_group_role_model', 'adminGroupRoleModel');
		$this->load->model('Admin_group_model', 'adminGroupModel');
		//inject dependencies
		$this->adminUserRepository = $adminUserRepository;
		$this->adminModuleRepository = $adminModuleRepository;
		//set model for repository
		$this->adminUserRepository->setModel($this->adminUserModel);
		$this->adminUserRepository->setAdminGroupRoleModule($this->adminGroupRoleModel);
		$this->adminModuleRepository->setModel($this->moduleModel);

		//check authen
		$this->auth();
		//get sidebar menu
		$adminName = $this->session->userdata('adminName');
		$listModule = $this->getModuleList();
		//add menu config module to sidebar
		if (CommonHelperFn\isDevEnv() && $this->checkSuperAdmin()) {
			$configModule = new stdClass();
			$configModule->id = -1;
			$configModule->name = 'Cấu hình module';
			$configModule->controller = 'config-module';
			$listModule[] = \Solid\Collections\AdminModule::create($configModule);
		}
		$this->dataView['sidebarMenu'] = SidebarMenu::initialize($adminName, $listModule,$this->adminModule)->render();
	}

	public function auth()
	{
		try {
			$this->checkAdminAuth();
		} catch (ErrorException $e) {
			redirect(RewriteUrlFn\admin_login());
		}
	}

	public function accessDenied()
	{
		//clear session
		$this->session->sess_destroy();
		show_error('Access denied', 403);
	}

	protected function checkAdminAuth()
	{
		$adminName = $this->session->userdata('admin');
		$adminPass = $this->session->userdata('password');
		if (!$adminName) {
			throw new ErrorException('Authentication failed', 403);
		}
		$adminUser = $this->adminUserRepository->findByLoginname($adminName);
		if (!$adminUser) {
			throw new ErrorException('Incorrect username', 403);
		}
		if (!$this->checkHashPassword($adminPass, $adminUser->getHash(), $adminUser->getPassword())) {
			throw new ErrorException('Incorrect password', 403);
		}
		return true;
	}

	/**
	 * @param string $action
	 * @return bool|mixed
	 * @throws ErrorException
	 */
	protected function checkPermission($action)
	{
		if (!$this->adminModule) {
			throw new ErrorException("Module name is require");
		}

		$listModule = $this->adminModuleRepository->findAllByName($this->adminModule);
		if (!$listModule) {
			throw new ErrorException("Module is not exist");
		}

		//get group admin
		$adminUser = $this->adminUserRepository->findByLoginname($this->session->userdata('admin'));
		if (!$adminUser) {
			throw new ErrorException("Admin user not found");
		}
		//if super admin return true
		if ($this->checkSuperAdmin() && $adminUser->getIsAdmin()) {
			return true;
		}
		$listAction = \Solid\Collections\AdminUser::ROLE;
		if (!in_array($action, $listAction)) {
			throw new ErrorException("Permission $action not found");
		}
		//get permission with admin module
		//nếu method trùng với action thì search trong module trước
		if($this->router->method == $action) {
			$module = $this->adminModuleRepository->findByNameAndMethod($this->adminModule,$action);
			if($module) {
				return $this->adminUserRepository->getGroupRoleDetail($adminUser->getGroup()->getId(), $module->getId(),$action);
			}
		}
		//nếu không có method tương ứng với action thì lặp qua các module để check quyền
		$perm = false;
		foreach($listModule as $module) {
			$perm = $this->adminUserRepository->getGroupRoleDetail($adminUser->getGroup()->getId(), $module->getId(), $action);
			if($perm) {
				break;
			}
		}
		return $perm;
	}

	protected function checkSuperAdmin()
	{
		return $this->session->userdata('isAdmin');
	}
	protected function getSessionGroupId()
	{
		return $this->session->userdata('groupId');
	}


	protected function getModuleList()
	{
		$dataList = [];
		//get list module of group
		$groupId = $this->session->userdata('groupId');
		if ($this->checkSuperAdmin()) {
			//list all module
			$dataList = $this->adminModuleRepository->getAllModule(0);
		} else {
			//list module in role by group
			$dataList = $this->adminUserRepository->getModuleByGroup($groupId);
		}
		return $dataList;
	}

	/**
	 * TODO refactor
	 */
	protected function show404Page()
	{
		show_404();
	}

	/**
	 * TODO refactor
	 * @param $message
	 */
	protected function showErrorPage($message)
	{
		show_error($message);
	}

	public function before()
	{
		parent::before(); // TODO: Change the autogenerated stub
		if ($this->adminModule == 'dashboard' || $this->adminModule == 'config-module') {
			return;
		}
		$arguments = func_get_args();
		if(!$arguments || empty($arguments[0])) {
			throw new ErrorException("Before function require argument");
		}
		$method = $arguments[0];
		array_shift($arguments);
//		if (!$this->checkPermission('view')) {
//			$this->goToNotPermissionPage();
//			return;
//		}
		switch ($method) {
			case 'add':
			case 'postAdd':
				$hasPermission = $this->checkPermission('add');
				break;
			case 'edit':
			case 'postEdit':
			case 'ajaxUpdate':
				$hasPermission = $this->checkPermission('edit');
				break;
			case 'ajaxDelete':
				$hasPermission = $this->checkPermission('delete');
				break;
			case 'export':
				$hasPermission = $this->checkPermission('export');
				break;
			case 'import':
				$hasPermission = $this->checkPermission('import');
				break;
			default:
				$hasPermission = $this->checkPermission('view');
				break;
		}
		if (!$hasPermission) {
			$this->goToNotPermissionPage();
			return;
		}
	}

	public function after()
	{
		parent::after(); // TODO: Change the autogenerated stub
	}

	protected function goToNotPermissionPage()
	{
		if ($this->isAjax) {
			$arrayReturn = ['error' => 1, 'msg' => 'Bạn không có quyền truy cập chức năng này'];
			//show error like json
			set_status_header(403);
			echo json_encode($arrayReturn);
			die();
		} else {
			set_status_header(403);
			echo $this->blade->render('errors.admin_permission', [], TRUE);
			exit();
		}
	}

}

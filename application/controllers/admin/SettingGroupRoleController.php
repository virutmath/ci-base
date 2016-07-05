<?php
use Solid\Repositories\AdminUserRepository;
use Solid\Repositories\AdminModuleRepository;
use Solid\Builder\TableAdmin;
use Solid\Repositories\AdminGroupRepository;

/**
 * Created by Kelvin <kiendt@i-com.vn>
 * Date: 6/20/2016
 * Time: 1:49 PM
 */

/**
 * Class SettingGroupRoleController
 * @property Admin_group_role_model $adminGroupRoleModel
 * @property Admin_group_model $adminGroupModel
 */
class SettingGroupRoleController extends AdminController
{
	protected $adminModule = 'setting-group-role';

	/**
	 * @var AdminGroupRepository
	 */
	private $adminGroupRepository;

	public function __construct(AdminUserRepository $adminUserRepository,
	                            AdminModuleRepository $adminModuleRepository,
	                            AdminGroupRepository $adminGroupRepository)
	{
		parent::__construct($adminUserRepository, $adminModuleRepository);
		$this->load->model('Admin_group_model', 'adminGroupModel');

		$this->adminGroupRepository = $adminGroupRepository;
		$this->adminGroupRepository->setModel($this->adminGroupModel);
	}

	public function index()
	{
		$groupId = $this->input->get('group', TRUE);
		if (!$groupId) {
			//select group
			$list = $this->adminGroupRepository->getAll();
			$this->dataView['list'] = $list;
			$this->blade->render('admin.select-group', $this->dataView);
		} else {
			//select module
			$moduleList = $this->adminModuleRepository->getAll();
			/**
			 * @var \Solid\Collections\AdminModule[] $moduleList
			 */
			$moduleList = $this->parseMultiLevelData($moduleList);
			foreach ($moduleList as $module) {
				//get role
				$module->roleTmp = $this->adminUserRepository->getGroupRoleDetail($this->getSessionGroupId(), $module->getId());
			}
//			ddd($settingModuleList);
			//table admin
			$tableAdmin = TableAdmin::initialize($moduleList);
			$tableAdmin->column('id', 'ID');
			$tableAdmin->column('publicName', 'Module name');
			$tableAdmin->column('roleTmp.role_view', 'View', 'checkbox');
			$tableAdmin->column('roleTmp.role_add', 'Add', 'checkbox');
			$tableAdmin->column('roleTmp.role_edit', 'Edit', 'checkbox');
			$tableAdmin->column('roleTmp.role_delete', 'Delete', 'checkbox');
			$tableAdmin->column('roleTmp.role_import', 'Import', 'checkbox');
			$tableAdmin->column('roleTmp.role_export', 'Export', 'checkbox');
			$this->dataView['tableAdmin'] = $tableAdmin->render();
			$this->blade->render('admin.setting-group-role',$this->dataView);
		}
	}

	public function ajaxUpdate()
	{
		$arrayReturn = [];
		$recordId = $this->input->post('record',TRUE);
		$field = $this->input->post('field',TRUE);
		if(in_array($field,\Solid\Collections\AdminUser::ROLE)) {
			$result = $this->adminUserRepository->updateRole($this->getSessionGroupId(),$recordId,$field);
			if($result) {
				$arrayReturn['success'] = 1;
				$arrayReturn['msg'] = 'Cập nhật thành công';
				echo json_encode($arrayReturn);
			}else{
				set_status_header(400);
				$arrayReturn = ['error'=>1,'msg'=>'Bad request'];
				echo json_encode($arrayReturn);
			}
			die();
		}else {
			set_status_header(400);
			$arrayReturn = ['error'=>1,'msg'=>'Bad request'];
			echo json_encode($arrayReturn);
			die();
		}
	}
}
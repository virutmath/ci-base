<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 6/14/2016
 * Time: 9:17 AM
 */

namespace Solid\Repositories;

use MongoDB\Driver\Exception\Exception;
use Solid\Collections\AdminGroup;
use Solid\Collections\AdminModule;
use Solid\Collections\AdminUser;

/**
 * Class AdminUserRepository
 * @package Solid\Repositories
 * @property \Admin_user_model $model
 * @property \Admin_group_role_model $adminGroupRoleModel
 */
class AdminUserRepository extends BaseRepository
{
	public $adminGroupRoleModel;

	public function setAdminGroupRoleModule(\MY_Model $model)
	{
		$this->adminGroupRoleModel = $model;
	}

	public function save()
	{
		// TODO: Implement save() method.
	}

	public function getAll()
	{
		// TODO: Implement getAll() method.
	}

	public function findById($id)
	{
		// TODO: Implement findById() method.
		$this->checkModel();
	}

	public function findByLoginname($loginname)
	{
		$this->checkModel();
		$modelRecord = $this->model->fields('*')
			->where('loginname', $loginname)
			->with('adminGroup')
			->get();
		if (!$modelRecord) {
			return null;
		}
		if ($modelRecord->adminGroup) {
			$modelRecord->group = AdminGroup::create($modelRecord->adminGroup);
		}
		return AdminUser::create($modelRecord);
	}

	/**
	 * @param $groupId
	 * @param $moduleId
	 * @param string $role
	 * @return bool|\stdClass
	 * @throws \ErrorException
	 * @throws \Exception
	 */
	public function getGroupRoleDetail($groupId, $moduleId, $role = '')
	{
		$this->checkModel();
		if (!$role) {
			$listRole = AdminUser::ROLE;
			$default = new \stdClass();
			foreach ($listRole as $role) {
				$default->{'role_' . $role} = 0;
			}
			$query = $this->adminGroupRoleModel->get(['group_id' => $groupId, 'module_id' => $moduleId]);
			return $query ?: $default;
		}
		if (!in_array($role, AdminUser::ROLE)) {
			throw new \ErrorException("Role not exists");
		}
		$role = 'role_' . $role;
		$query = $this->adminGroupRoleModel->fields($role)->get(['group_id' => $groupId, 'module_id' => $moduleId]);
		return $query ? $query->{$role} : false;
	}

	/**
	 * @param $groupId
	 * @return AdminModule[]
	 */
	public function getModuleByGroup($groupId)
	{
		$this->checkModel();
		$listRecord = $this->adminGroupRoleModel->with('module')->get_all(['group_id'=>$groupId]);
		$listModule = [];
		if($listRecord) {
			foreach ($listRecord as $item) {
				$roles = AdminUser::ROLE;
				//nếu module có ít nhất 1 quyền thì add vào list module
				$checkAccess = false;
				foreach ($roles as $role) {
					if($item->{'role_' . $role}) {
						$checkAccess = true;
						break;
					}
				}
				if($checkAccess) {
					$listModule[] = AdminModule::create($item->module);
				}
			}
		}
		//sắp xếp lại list module theo trật tự parent - child
		$reorderList = [];
		foreach($listModule as $item) {
			if($item->getParentId() == 0) {
				//tìm lại trong list có module nào có parent là item này thì thêm vào child
				$children = [];
				foreach ($listModule as $child){
					if($child->getParentId() == $item->getId()) {
						$children[] = $child;
					}
				}
				$item->setModuleChild($children);
				$reorderList[] = $item;
			}
		}
		return $reorderList;
	}

	public function updateRole($groupId, $moduleId, $role)
	{
		if (!in_array($role, AdminUser::ROLE)) {
			throw new \ErrorException("Role not exists");
		}
		$role = 'role_' . $role;
		//check role
		$query = $this->adminGroupRoleModel->get(['group_id' => $groupId, 'module_id' => $moduleId]);
		if ($query) {
			//đã tồn tại role => update
			return $this->adminGroupRoleModel->update([$role => abs(1 - $query->{$role})], ['group_id' => $groupId, 'module_id' => $moduleId]);
		}
		//chưa tồn tại => insert
		$data = ['module_id' => $moduleId, 'group_id' => $groupId];
		foreach (AdminUser::ROLE as $item) {
			$data['role_' . $item] = 0;
		}
		$data[$role] = 1;
		return $this->adminGroupRoleModel->insert($data);
	}
}
<?php

namespace Solid\Repositories;


use Solid\Collections\AdminGroup;

/**
 * Class AdminGroupRepository
 * @package Solid\Repositories
 * @property \MY_Model $model
 */
class AdminGroupRepository extends BaseRepository
{
	public function save()
	{
		// TODO: Implement save() method.
	}

	public function getAll()
	{
		// TODO: Implement getAll() method.
		$result = [];
		$this->checkModel();
		$list = $this->model->get_all();
		if($list) {
			foreach ($list as $item) {
				$result[] = AdminGroup::create($item);
			}
		}
		return $result;
	}

	public function findById($id)
	{
		// TODO: Implement findById() method.
	}

}
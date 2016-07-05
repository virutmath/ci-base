<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 6/14/2016
 * Time: 3:40 PM
 */

namespace Solid\Repositories;

use Solid\Collections\AdminModule;

/**
 * Class AdminModuleRepository
 * @package Solid\Repositories
 * @property \Module_model $model
 */
class AdminModuleRepository extends BaseRepository
{
    private $data;

    /**
     * set data for save function
     * @param string $name
     * @param string $controller
     * @param string $method
     * @param int $parentId
     * @param int $order
     * @param int $active
     */
    public function setDataInsert($name, $controller = '', $method = '', $parentId = 0, $order = 0, $active = 1)
    {
        $this->data = [
            'name' => $name,
            'controller' => $controller,
            'method' => $method,
            'parent_id' => $parentId,
            'order' => $order,
            'active' => $active
        ];
    }

    public function save()
    {
        // TODO: Implement save() method.
        $this->checkModel();
        return $this->model->insert($this->data);
    }

    /**
     * @param array $data
     * @throws \Exception
     * @return int|bool
     */
    public function updateById($data)
    {
        $this->checkModel();
        return $this->model->update($data, ['id' => $data['id']]);
    }

    public function getAll()
    {
        // TODO: Implement getAll() method.
	    $this->checkModel();
	    return $this->getAllModule(0);
    }

    /**
     * Get all module with parent id
     * @param int $parent_id
     * @return AdminModule[]
     */
    public function getAllModule($parent_id = 0)
    {
	    $this->checkModel();
        $list = [];
        $parent_id = intval($parent_id);
        $query = $this->model->fields('*')
            ->where('parent_id', $parent_id)
            ->get_all();
        if (!$query) {
            return [];
        }
        foreach ($query as $item) {
            $module = AdminModule::create($item);
            if (property_exists($item, 'has_child') && !$item->has_child) {
                $module->setModuleChild([]);
            } else {
                //try to get child
                $module->setModuleChild($this->getAllModule($item->id));
            }
            $list[] = $module;
        }
        return $list;
    }

	
    /**
     * @param int $id
     * @throws \Exception
     * @return AdminModule|null
     */
    public function findById($id)
    {
        // TODO: Implement findById() method.
        $this->checkModel();
        $item = $this->model->get(['id' => $id]);
        return $item ? AdminModule::create($item) : null;
    }

	/**
	 * @param string $controllerName
	 * @return null|AdminModule
	 * @throws \Exception
	 */
	public function findAllByName($controllerName)
	{
		$this->checkModel();
		$listModule = [];
		$list = $this->model->get_all(['controller'=>$controllerName]);
		if($list) {
			foreach ($list as $item) {
				$listModule[] = AdminModule::create($item);
			}
		}
		return $listModule;
	}
	
	public function findByNameAndMethod($controllerName, $method)
	{
		$this->checkModel();
		$item = $this->model->get(['controller'=>$controllerName,'method'=>$method]);
		return $item ? AdminModule::create($item) : null;
	}

    /**
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public function deleteById($id) {
        $this->checkModel();
        return $this->model->delete(['id'=>$id]);
    }
}
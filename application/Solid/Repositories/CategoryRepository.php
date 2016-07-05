<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 6/16/2016
 * Time: 9:55 AM
 */

namespace Solid\Repositories;
use Solid\Collections\Category;

/**
 * Class CategoryRepository
 * @package Solid\Repositories
 * @property \Category_model $model
 */
class CategoryRepository extends BaseRepository
{
    public function save()
    {
        // TODO: Implement save() method.
    }

    /**
     * @param array $data
     * @return int
     * @throws \Exception
     */
    public function addCategory($data) {
        $this->checkModel();
        $update_data = [];
        if (isset($data['name'])) {
            $update_data['name'] = htmlentities($data['name']);
        } else {
            throw new \ErrorException('Tên danh mục là bắt buộc', 500);
        }
        if (isset($data['parent_id'])) {
            $update_data['parent_id'] = intval($data['parent_id']);
        } else {
            $update_data['parent_id'] = 0;
        }
        if (isset($data['active'])) {
            $update_data['active'] = $data['active'] ? Category::ACTIVE : Category::INACTIVE;
        } else {
            $update_data['active'] = Category::INACTIVE;
        }
        if (isset($data['title'])) {
            $update_data['title'] = htmlentities($data['title']);
        }
        if (isset($data['description'])) {
            $update_data['description'] = htmlentities($data['description']);
        }
        if (isset($data['keyword'])) {
            $update_data['keyword'] = htmlentities($data['keyword']);
        }
        if (isset($data['icon'])) {
            $update_data['icon'] = intval($data['icon']);
        }
        if (isset($data['image']) && $data['image']) {
            $update_data['image'] = $data['image'];
        }
        return $this->model->insert($update_data);
    }

    public function getAll()
    {
        // TODO: Implement getAll() method.
        $this->checkModel();
    }

    /**
     * @param int $parent_id
     * @return array
     * @throws \Exception
     */
    public function getAllCategory($parent_id = 0) {
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
            $category = Category::create($item);
            if (property_exists($item, 'has_child') && !$item->has_child) {
                $category->setChild([]);
            } else {
                //try to get child
                $category->setChild($this->getAllCategory($item->id));
            }
            $list[] = $category;
        }
        return $list;
    }

    /**
     * @param $id
     * @return Category
     * @throws \Exception
     */
    public function findById($id)
    {
        // TODO: Implement findById() method.
        $this->checkModel();
        $data = $this->model->fields('*')
                            ->get(['id'=>$id]);
        if(!$data) {
            throw new \Exception("Category not found");
        }
        return Category::create($data);
    }

    /**
     * @param $id
     * @return string
     * @throws \Exception
     */
    public function updateChild($id){
        $this->checkModel();
        if(!$id) {
            throw new \ErrorException("Category not found");
        }
        return $this->model->update(['has_child'=>Category::HAS_CHILD],$id);
    }
    
    public function removeChild($id)
    {
        $this->checkModel();
        if(!$id) {
            throw new \ErrorException("Category not found");
        }
        return $this->model->update(['has_child'=>Category::HAS_NO_CHILD],$id);
    }

    /**
     * @param array $data
     * @return \str
     * @throws \Exception
     */
    public function updateById($data)
    {
        $this->checkModel();
        return $this->model->update($data, ['id' => $data['id']]);
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
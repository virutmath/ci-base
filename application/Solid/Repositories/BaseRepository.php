<?php
namespace Solid\Repositories;

/**
 * Class BaseRepository
 * @package Solid\Repositories
 */
abstract class BaseRepository implements Repository {
	protected $model;
	/**
	 * @param \MY_Model $model
	 * @return mixed
	 */
	public function setModel(\MY_Model $model)
	{
		// TODO: Implement setModel() method.
		$this->model = $model;
	}


	/**
	 * Check when model is set
	 */
	public function checkModel() {
		if(!$this->model) {
			throw new \Exception("Model not set in this repository");
		}
	}
	
	public function toggleBooleanField($field, $id)
	{
		$this->checkModel();
		return $this->model->update([$field => "ABS({$field} - 1)"], $id, FALSE);
	}
	
	abstract public function save();
	
	abstract public function getAll();
	
	abstract public function findById($id);

}
<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 6/14/2016
 * Time: 3:37 PM
 */

namespace Solid\Collections;


class AdminModule implements CollectionInterface
{
    private $id;
    private $name;
    private $controller;
    private $method;
    private $parentId;
    /**
     * @var AdminModule[] $child
     */
    private $child;
    private $order;
    private $active;
	
	public $roleTmp;
    /**
     * @var string $publicName Allow custom name
     */
    public $publicName;

    public function __construct($modelRecord)
    {
        $listProperty = ['id', 'name', 'controller', 'method', 'parent_id', 'order', 'active'];
        foreach ($listProperty as $property) {
            if (property_exists($modelRecord, $property)) {
                $this->{camel_case($property)} = $modelRecord->{$property};
            }
        }
        if (property_exists($modelRecord, 'name')) {
            $this->publicName = $this->name;
        }
    }

    public static function create($modelRecord)
    {
        // TODO: Implement create() method.
        return new AdminModule($modelRecord);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return AdminModule[]
     */
    public function getChild()
    {
        return $this->child;
    }

    /**
     * @return mixed
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    public function __get($name)
    {
        // TODO: Implement __get() method.
        return $this->{$name};
    }

    /**
     * @param AdminModule[] $listChild
     */
    public function setModuleChild($listChild)
    {
        $this->child = $listChild;
    }

    /**
     * @return string
     */
    public function getAdminModuleUrl()
    {
        $url = '';
        if ($this->controller) {
            $url .= '/admin/' . $this->controller;
        }
        if ($this->method) {
            $url .= '/' . $this->method;
        }
        return $url;
    }

    /**
     * @param $moduleName
     * @return string
     */
    public static function getAdminModuleIndexUrl($moduleName)
    {
        return '/admin/' . $moduleName;
    }

    /**
     * @param $moduleName
     * @return string
     */
    public static function getAdminModuleAddUrl($moduleName)
    {
        return '/admin/' . $moduleName . '/add';
    }

    /**
     * @param $moduleName
     * @param $id
     * @return string
     */
    public static function getAdminModuleEditUrl($moduleName, $id)
    {
        return '/admin/' . $moduleName . '/edit/' . $id;
    }

    /**
     * @param $moduleName
     * @return string
     */
    public static function getAdminModuleDeleteUrl($moduleName)
    {
        return '/admin/' . $moduleName . '/delete';
    }
}
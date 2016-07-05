<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 6/16/2016
 * Time: 9:55 AM
 */

namespace Solid\Collections;


class Category extends BaseCollection implements CollectionInterface
{
	protected $id;
	protected $name;
    public $publicName;
	protected $parentId;
    /**
     * @var Category
     */
    protected $parentCategory;
	protected $active;
	protected $title;
	protected $description;
	protected $keyword;
	protected $image;
	protected $icon;
	protected $hasChild;
	protected $child;
	protected $createdAt;
	protected $updatedAt;
	protected $deletedAt;

    const ICON = [
        0=>'fa fa-folder',
        1=>'fa fa-folder-o'
    ];
    const INACTIVE = 0;
    const ACTIVE = 1;
    const HAS_CHILD = 1;
    const HAS_NO_CHILD = 0;


    public function __construct($modelRecord)
    {
        $this->listProperties = [
            'id',
            'name',
            'parent_id',
            'active',
            'title',
            'description',
            'keyword',
            'image',
            'icon',
            'has_child',
            'created_at',
            'updated_at',
            'deleted_at'
        ];
        parent::__construct($modelRecord);
    }

    public static function create($modelRecord)
    {
        // TODO: Implement create() method.
        return new Category($modelRecord);
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
	public function getKeyword()
	{
		return $this->keyword;
	}

	/**
	 * @return mixed
	 */
	public function getImage()
	{
		return \RewriteUrlFn\get_picture_path($this->image);
	}

	/**
	 * @return mixed
	 */
	public function getIcon()
	{
		return (int)$this->icon;
	}

    /**
     * @return mixed
     */
    public function getPublicName()
    {
        return $this->publicName;
    }

    /**
     * @return mixed
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * @return Category
     */
    public function getParentCategory()
    {
        return $this->parentCategory;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getHasChild()
    {
        return $this->hasChild;
    }
    

    /**
     * @return mixed
     */
    public function getChild()
    {
        return $this->child;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return mixed
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    function __get($name)
    {
        // TODO: Implement __get() method.
        $method = 'get'.ucfirst(camel_case($name));
	    if(is_callable([$this, $method])){
		    return $this->$method();
	    }else{
		    return $this->{$name};
	    }
    }

    /**
     * @param Category $parentCategory
     */
    public function setParentCategory($parentCategory)
    {
        $this->parentCategory = $parentCategory;
    }

    /**
     * @param mixed $child
     */
    public function setChild($child)
    {
        $this->child = $child;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 6/14/2016
 * Time: 9:08 AM
 */

namespace Solid\Collections;


class AdminUser implements CollectionInterface
{
    protected $id;
    protected $name;
    protected $loginname;
    
    /**
     * @var AdminGroup $group
     */
    protected $group;
    
    protected $password;
    protected $hash;
    protected $email;
    protected $address;
    protected $phone;
    protected $isAdmin;
    protected $active;
    protected $createdAt;
    protected $updatedAt;
    protected $deletedAt;
	
	const ROLE = ['view','add','edit','delete','import','export'];

    public function __construct($modelRecord)
    {
        $listProperty = ['id','name','loginname','group'
            ,'created_at','updated_at','deleted_at','password','hash','email'
            ,'address','is_admin','active'];
        foreach($listProperty as $property){
            if(property_exists($modelRecord,$property)) {
                $this->{camel_case($property)} = $modelRecord->{$property};
            }
        }
    }

    /**
     * @param $modelRecord
     * @return AdminUser
     */
    public static function create($modelRecord)
    {
        return new AdminUser($modelRecord);
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
    public function getLoginname()
    {
        return $this->loginname;
    }

    /**
     * @return AdminGroup
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return mixed
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @return mixed
     */
    public function getIsAdmin()
    {
        return $this->isAdmin;
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
    
}
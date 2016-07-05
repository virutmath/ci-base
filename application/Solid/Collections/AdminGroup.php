<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 6/14/2016
 * Time: 9:14 AM
 */

namespace Solid\Collections;


class AdminGroup implements CollectionInterface
{
    protected $id;
    protected $name;
    public function __construct($modelRecord)
    {
        $this->id = $modelRecord->id;
        $this->name = $modelRecord->name;
    }

    public static function create($modelRecord)
    {
        // TODO: Implement create() method.
        return new AdminGroup($modelRecord);
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

}
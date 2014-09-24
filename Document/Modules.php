<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 23/09/14
 * Time: 16:25
 */

namespace Imagana\ResourcesCreatorBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM ;
use Doctrine\Common\Collections\ArrayCollection ;


class Modules {

    /** @ODM\Id */
    private $id ;

    /** @ODM\String */
    private $title;

    /** @ODM\String */
    private $support;

    /** @ODM\String */
    private $difficulties;

    /** @ODM\String */
    private $pedagogicalFlow;

    /**
     * @param mixed $difficulties
     */
    public function setDifficulties($difficulties)
    {
        $this->difficulties = $difficulties;
    }

    /**
     * @return mixed
     */
    public function getDifficulties()
    {
        return $this->difficulties;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $pedagogicalFlow
     */
    public function setPedagogicalFlow($pedagogicalFlow)
    {
        $this->pedagogicalFlow = $pedagogicalFlow;
    }

    /**
     * @return mixed
     */
    public function getPedagogicalFlow()
    {
        return $this->pedagogicalFlow;
    }

    /**
     * @param mixed $support
     */
    public function setSupport($support)
    {
        $this->support = $support;
    }

    /**
     * @return mixed
     */
    public function getSupport()
    {
        return $this->support;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }


} 
<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 22/09/14
 * Time: 16:00
 */

namespace Imagana\ResourcesCreatorBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM ;
use Doctrine\Common\Collections\ArrayCollection ;


/**
 * Class Level
 * @ODM\Document(
 *     collection="Level",
 *     repositoryClass="Imagana\ResourcesCreatorBundle\Repository\LevelRepository"
 * )
 */
class Level {

    /** @ODM\Id */
    private $id ;

    /** @ODM\String */
    private $title;

    /** @ODM\String */
    private $technicalName ;

    /** @ODM\Date */
    private $creationDate;

    /** @ODM\String */
    private $creator;

    /** @ODM\String */
    private $description;

    /** @ODM\String */
    private $levelWords;

    /** @ODM\String */
    private $moreInformation;

    /** @ODM\Boolean */
    private $isActive;

    /** @ODM\ObjectId */
    private $levelCategory ;

    /** @ODM\Collection */
    private $pedagogicalPurpose ;


    public function __construct(){
        $this->pedagogicalPurpose= Array();
    }

    /**
     * @param mixed $creationDate
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    }

    /**
     * @return mixed
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * @param mixed $creator
     */
    public function setCreator($creator)
    {
        $this->creator = $creator;
    }

    /**
     * @return mixed
     */
    public function getCreator()
    {
        return $this->creator;
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
     * @param mixed $technicalName
     */
    public function setTechnicalName($technicalName)
    {
        $this->technicalName = $technicalName;
    }

    /**
     * @return mixed
     */
    public function getTechnicalName()
    {
        return $this->technicalName;
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

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $levelWords
     */
    public function setLevelWords($levelWords)
    {
        $this->levelWords = $levelWords;
    }

    /**
     * @return mixed
     */
    public function getLevelWords()
    {
        return $this->levelWords;
    }

    /**
     * @param mixed $moreInformation
     */
    public function setMoreInformation($moreInformation)
    {
        $this->moreInformation = $moreInformation;
    }

    /**
     * @return mixed
     */
    public function getMoreInformation()
    {
        return $this->moreInformation;
    }

    /**
     * @param mixed $pedagogicalPurpose
     */
    public function setPedagogicalPurpose($pedagogicalPurpose)
    {
        $this->pedagogicalPurpose = $pedagogicalPurpose;
    }

    /**
     * @return mixed
     */
    public function getPedagogicalPurpose()
    {
        return $this->pedagogicalPurpose;
    }

    /**
     * @param mixed $levelCategory
     */
    public function setLevelCategory($levelCategory)
    {
        $this->levelCategory = $levelCategory;
    }

    /**
     * @return mixed
     */
    public function getLevelCategory()
    {
        return $this->levelCategory;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

}
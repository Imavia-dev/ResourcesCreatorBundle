<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 22/09/14
 * Time: 16:00
 * User: jerome
 * Date: 24/09/14
 * Time: 09:20
 */

namespace Imagana\ResourcesCreatorBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM ;

/**
 * Class Category
 * @ODM\Document(
 *     collection="PedagogicalPurpose",
 *     repositoryClass="Imagana\ResourcesCreatorBundle\Repository\PedagogicalPurposeRepository"
 * )
 */

class PedagogicalPurpose {

    /** @ODM\Id */
    private $id ;

    /** @var  @ODM\String */
    private $description;

    /** @ODM\Date */
    private $creationDate;

    /** @ODM\String */
    private $creator;

    /** @ODM\Boolean */
    private $isActive;

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
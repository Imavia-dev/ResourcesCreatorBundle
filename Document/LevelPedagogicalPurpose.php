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
 *     collection="LevelPedagogicalpurpose",
 *     repositoryClass="Imagana\ResourcesCreatorBundle\Repository\LevelPedagogicalPurposeRepository"
 * )
 */
class LevelPedagogicalPurpose {

    /** @ODM\Id */
    private $id ;

    /** @ODM\ObjectId */
    private  $levelId;

    /** @ODM\ObjectId */
    private $pedagogicalPurposeId;

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
     * @param mixed $levelId
     */
    public function setLevelId($levelId)
    {
        $this->levelId = $levelId;
    }

    /**
     * @return mixed
     */
    public function getLevelId()
    {
        return $this->levelId;
    }

    /**
     * @param mixed $pedagogicalPurposeId
     */
    public function setPedagogicalPurposeId($pedagogicalPurposeId)
    {
        $this->pedagogicalPurposeId = $pedagogicalPurposeId;
    }

    /**
     * @return mixed
     */
    public function getPedagogicalPurposeId()
    {
        return $this->pedagogicalPurposeId;
    }

}
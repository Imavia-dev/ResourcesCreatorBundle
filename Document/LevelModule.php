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


/**
 * Class Modules
 * @ODM\Document(
 *     collection="LevelModule",
 *     repositoryClass="Imagana\ResourcesCreatorBundle\Repository\LevelModuleRepository"
 * )
 */
class LevelModule {

    /** @ODM\Id */
    private $id ;

    /** @ODM\ObjectId */
    private  $levelId;

    /** @ODM\ObjectId */
    private $moduleId;

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
     * @param mixed $moduleId
     */
    public function setModuleId($moduleId)
    {
        $this->moduleId = $moduleId;
    }

    /**
     * @return mixed
     */
    public function getModuleId()
    {
        return $this->moduleId;
    }



} 
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
 * Class PedagogicalPurpose
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

}
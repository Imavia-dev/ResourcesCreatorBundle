<?php
/**
 * Created by PhpStorm.
 * User: jerome
 * Date: 24/09/14
 * Time: 09:20
 */

namespace Imagana\ResourcesCreatorBundle\Document;


/**
 * Class Category
 * @ODM\Document(
 *     collection="PedagogicalPurpose",
 *     repositoryClass="Imagana\ResourcesCreatorBundle\Repository\PedagogicalPurpose"
 * )
 */

class PedagogicalPurpose {

    /** @ODM\Id */
    private $id ;


    /** @ODM\string */
    private $description ;

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
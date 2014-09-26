<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 22/09/14
 * Time: 15:15
 */

namespace Imagana\ResourcesCreatorBundle\FormModel;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContext;

class LevelModel {

    /**
     * @assert\Length(
     *     min=1,
     *     minMessage="Le nom de la catégorie doit faire plus de {{ limit }} caractères",
     * )
     * @assert\NotBlank
     *
     * @var
     */
    private $title;

    /**
     * @assert\Length(
     *     min=1,
     *     minMessage="Le nom technique doit faire plus de {{ limit }} caractères",
     * )
     * @assert\NotBlank
     *
     * @var
     */
    private $technicalName;

    /**
     * @var
     */
    private $levelCategory;

    /**
     * @var
     */
    private $pedagogicalPurpose;

    /**
     * @var
     */
    private $description;

    /**
     * @assert\Length(
     *     min=1,
     *     minMessage="La liste des mots du niveau doit être supérieur à {{ limit }} caractères",
     * )
     * @assert\NotBlank
     *
     * @var
     */
    private $levelWords;

    /**
     * @var
     */
    private $moreInformation;

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

}
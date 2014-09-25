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

class ModuleModel {

    /**
     * @assert\Length(
     *     min=1,
     *     minMessage="Le titre du module doit faire plus de {{ limit }} caractères",
     * )
     * @assert\NotBlank
     *
     * @var
     */
    private $title;

    /**
     * @var
     */
    private $support;

    /**
     * @var
     */
    private $difficulties;

    /**
     * @var
     */
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
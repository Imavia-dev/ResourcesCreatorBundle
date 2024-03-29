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

class PedagogicalPurposeModel {

    /**
     * @assert\Length(
     *     min=1,
     *     max=100,
     *     minMessage="L'objectif pédagogique doit faire plus de {{ limit }} caractères",
     *     maxMessage="L'objectif pédagogique ne doit pas dépasser {{ limit }} caractères",
     * )
     * @assert\NotBlank
     *
     * @var
     */
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

}
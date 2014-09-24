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
     *     max=20,
     *     minMessage="Le nom de la catégorie doit faire plus de {{ limit }} caractères",
     *     maxMessage="Le nom de la catégorie ne doit pas dépasser {{ limit }} caractères",
     * )
     * @assert\NotBlank
     *
     * @var
     */
    private $title;

    /**
     * @assert\Length(
     *     min=1,
     *     max=20,
     *     minMessage="Le nom technique doit faire plus de {{ limit }} caractères",
     *     maxMessage="Le nom technique ne doit pas dépasser {{ limit }} caractères",
     * )
     * @assert\NotBlank
     *
     * @var
     */
    private $technicalName ;

    /**
     * @var
     */
    private $description;

    /**
     * @var
     */
    private $levelWords;

    /**
     * @var
     */
    private $moreInformation;

}
<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 22/09/14
 * Time: 15:19
 */

namespace Imagana\ResourcesCreatorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class LevelType extends AbstractType {

    public function buildForm(FormBuilderInterface $fb ,array $options){
        $fb->add('title', 'text', array(
            'required' => true,
            'label' => "Nom du niveau",
            'attr' => array('placeholder' => 'Nom du niveau'),
            ))
            ->add('technicalName', 'text', array(
                'required' => true,
                'label' => "Nom technique du niveau",
                'attr' => array('placeholder' => 'Nom technique du niveau'),
            ))
            ->add('LevelCategory', 'document', array(
                'class' => 'Imagana\ResourcesCreatorBundle\Document\LevelCategory',
                'property' => 'description',
                'required' => true,
                'label' => "Catégorie"
            ))
            /*->add('Modules', 'document', array(
                'class' => 'Imagana\ResourcesCreatorBundle\Document\Modules',
                'property' => 'title',
                'required' => true,
                'label' => "Modules pédagogiques associés"
            ))*/;

        $form = $fb->getForm();

        return $form;
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
       return 'imagana_resourcescreatorbundle_imaganaleveltype';
    }

} 
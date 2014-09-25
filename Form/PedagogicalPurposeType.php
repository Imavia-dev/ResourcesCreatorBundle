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

class PedagogicalPurposeType extends AbstractType {

    public function buildForm(FormBuilderInterface $fb ,array $options){
        $fb->add('description', 'text', array(
            'required' => true,
            'label' => "Objectif pédagogique",
            'attr' => array('placeholder' => 'Décrivez l\'objectif pédagogiques'),
        ));
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
       return 'imagana_resourcescreatorbundle_imaganapedagogicalpurposetype';
    }

} 
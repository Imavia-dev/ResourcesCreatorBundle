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

class CategoryType extends AbstractType {

    public function buildForm(FormBuilderInterface $fb ,array $options){
        $fb->add('description', 'text', array(
            'required' => true,
            'label' => "Nom de la catégorie",
            'attr' => array('placeholder' => 'Nom de la catégorie'),
        ));

        /*$fb->add('categorie', 'collection', array(
            'type' => new imaganaCategoryType(),
            'allow_add' => true,
            'by_reference' => false
        ));*/

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
       return 'imagana_resourcescreatorbundle_imaganacategorytype';
    }

} 
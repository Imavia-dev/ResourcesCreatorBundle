<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 23/09/14
 * Time: 16:37
 */

namespace Imagana\ResourcesCreatorBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ModuleType extends AbstractType {

    public function buildForm(FormBuilderInterface $fb, array $options)
    {
        $fb->add('title', 'text', array(
            'required' => true,
            'label' => "Titre du module",
            'attr' => array('placeholder' => 'Titre du module'),
            ))
            ->add('support', 'textarea', array(
                'required' => false,
                'label' => "Support théorique",
                'attr' => array('placeholder' => 'Liste des différents supports utilisés'),
            ))
            ->add('difficulties', 'textarea', array(
                'required' => false,
                'label' => "Difficultés",
                'attr' => array('placeholder' => 'Difficultés'),
            ))
            ->add('pedagogicalFlow', 'textarea', array(
                'required' => false,
                'label' => "Déroulement pédagogique",
                'attr' => array('placeholder' => 'Décrivez ici le déroulement pédagogique du module'),
            ));
        /*$fb->add('levels','collection',array(
            'type'=> new ModuleLevelsType(),
            'allow_add' => true,
            'by_reference' => false
        ));*/
    }


    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
       return 'imagana_resourcescreatorbundle_modulestype';
    }

    /*public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
           'data_class' => 'Imagana\ResourcesCreatorBundle\Document\Module'
        ));
    }*/


} 
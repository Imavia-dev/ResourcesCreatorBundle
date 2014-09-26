<?php

namespace Imagana\ResourcesCreatorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation as DI;
use Imagana\ResourcesCreatorBundle\Form\PedagogicalPurposeType;
use Imagana\ResourcesCreatorBundle\FormModel\PedagogicalPurposeModel;
use Imagana\ResourcesCreatorBundle\Document\PedagogicalPurpose;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/*
 * Class PedagogicalPurposeController
 * @package Imagana\ResourcesCreatorBundle\Controller
 */

/**
 * @Route("admin/open/ImaganaResourcesCreator")
 */
class PedagogicalPurposeController extends Controller {

    /**
     * @Route(
     *     "/objectifs_pedagogiques/{page}",
     *     name="imagana_resources_creator_pedagogicalpurposes_list",
     *     defaults={"page" = "1"},
     *     requirements={"page" = "\d+"},
     * )
     * @Method({"GET"})
     * @Template("ImaganaResourcesCreatorBundle::pedagogicalPurposesManaging.html.twig")
     *
     */
    public function pedagogicalPurposesListAction($page = 1) {

        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {

            $dm = $this->container->get('doctrine_mongodb')->getManager();
            $pedagogicalPurposeRepo = $dm->getRepository("ImaganaResourcesCreatorBundle:PedagogicalPurpose");

            $pedagogicalPurposeCursor = $pedagogicalPurposeRepo->getAllActivePedagogicalPurposes();

            $result = array(
                "tab" => "objectifs",
                "objectifs" => $pedagogicalPurposeCursor
            );

            return $result;
        }
    }

    /**
     * @Route(
     *     "/objectifs_pedagogique/creer",
     *     name="imagana_resources_creator_pedagogicalpurpose_create",
     * )
     * @Method({"GET", "POST"})
     * @Template("ImaganaResourcesCreatorBundle::categoryManaging.html.twig")
     *
     */
    public function pedagocicalPurposeCreate(Request $request) {

        $formType = new PedagogicalPurposeType();
        $formModel = new PedagogicalPurposeModel();

        $form = $this->createForm($formType, $formModel);

        if($request->getMethod()=="POST"){
            $flashBag="notice" ;

            $form->handleRequest($request);
            if($form->isValid()){
                $dm = $this->container->get('doctrine_mongodb')->getManager();

                // Récupération des paramètres du formulaires
                $parameters = $request->request->all();
                $pedadogicalPurposeDescription = $parameters['imagana_resourcescreatorbundle_imaganapedagogicalpurposetype']['description'];
                $user = $this->container->get('security.context')->getToken()->getUser()->getUsername();

                $newPedagogicalPurpose = new PedagogicalPurpose();
                $newPedagogicalPurpose->setDescription($pedadogicalPurposeDescription);
                $newPedagogicalPurpose->setCreationDate(new \DateTime());
                $newPedagogicalPurpose->setCreator($user);
                $newPedagogicalPurpose->setIsActive(true);

                $dm->persist($newPedagogicalPurpose);
                $dm->flush($newPedagogicalPurpose);

                $flashBagContent = "L'objectif pédagogique " . $pedadogicalPurposeDescription . " a bien été créé";
            }else {
                $flashBag = "error";
                $flashBagContent = "Le formulaire est invalide, veuillez le corriger.";
            }

            $this->get('session')->getFlashBag()->add(
                $flashBag,
                $flashBagContent
            );
        }

        $result = array(
            "tab" => "objectifs",
            "form"=>$form->createView(),
            "route" => "imagana_resources_creator_pedagogicalpurpose_create",
            "previousRoute" => "imagana_resources_creator_pedagogicalpurposes_list"
        );

        return $result;
    }

}
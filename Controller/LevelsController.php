<?php

namespace Imagana\ResourcesCreatorBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Imagana\ResourcesCreatorBundle\Document\Level;
use Imagana\ResourcesCreatorBundle\Document\LevelPedagogicalPurpose;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Adapter\MongoAdapter;
use Pagerfanta\Adapter\DoctrineODMMongoDBAdapter;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Imagana\ResourcesCreatorBundle\FormModel\LevelModel;
use Imagana\ResourcesCreatorBundle\Form\LevelType;

/*
 * Class MainController
 * @package Imagana\ResourcesCreatorBundle\Controller
 */

/**
 * @Route("admin/open/ImaganaResourcesCreator")
 */
class LevelsController extends Controller {

    /**
     * @Route(
     *     "/niveaux/{page}",
     *     name="imagana_resources_creator_levels_list",
     *     defaults={"page" = "1"},
     *     requirements={"page" = "\d+"},
     * )
     * @Method({"GET"})
     * @Template("ImaganaResourcesCreatorBundle::levelsManaging.html.twig")
     *
     */
    public function levelsListAction($page = 1) {

        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {

            $dm = $this->container->get('doctrine_mongodb')->getManager();
            $levelsRepo = $dm->getRepository("ImaganaResourcesCreatorBundle:Level");

            $levelsarray = $levelsRepo->getAllActiveLevels();

            $result = array(
                "tab" => "niveaux",
                "niveaux" => $levelsarray
            );

            return $result;
        }
    }

    /**
     * @Route(
     *     "/niveau/creer",
     *     name="imagana_resources_creator_level_create",
     * )
     * @Method({"GET", "POST"})
     * @Template("ImaganaResourcesCreatorBundle::levelManaging.html.twig")
     *
     */
    public function levelCreate(Request $request) {

        $formType = new LevelType();
        $formModel = new LevelModel();

        $form = $this->createForm($formType, $formModel);

        if($request->getMethod()=="POST"){
            $flashBag="notice" ;

            $form->handleRequest($request);
            if($form->isValid()){

                $dm = $this->container->get('doctrine_mongodb')->getManager();
                $categoriesRepository = $dm->getRepository('ImaganaResourcesCreatorBundle:LevelCategory');
                $parameters = $request->request->all();

                // Recupération des Paramètres du Formulaires
                $levelTitle           = $parameters['imagana_resourcescreatorbundle_imaganaleveltype']['title'];
                $levelTechnicalName   = $parameters['imagana_resourcescreatorbundle_imaganaleveltype']['technicalName'];
                $levelDescription     = $parameters['imagana_resourcescreatorbundle_imaganaleveltype']['description'];
                $levelWords           = $parameters['imagana_resourcescreatorbundle_imaganaleveltype']['levelWords'];
                $levelmoreInfo        = $parameters['imagana_resourcescreatorbundle_imaganaleveltype']['moreInformation'];
                $levelCategory        = $parameters['imagana_resourcescreatorbundle_imaganaleveltype']['levelCategory'];
                $user = $this->container->get('security.context')->getToken()->getUser()->getUsername();

                $newlevel = new Level() ;

                $newlevel->setTitle($levelTitle);
                $newlevel->setTechnicalName($levelTechnicalName);
                $newlevel->setCreationDate(new \DateTime());
                $newlevel->setCreator($user);
                $newlevel->setDescription($levelDescription);
                $newlevel->setLevelWords($levelWords);
                $newlevel->setLevelCategory( new \MongoId($levelCategory));
                $newlevel->setMoreInformation($levelmoreInfo);
                $newlevel->setIsActive(true);

                $dm->persist($newlevel);
                $dm->flush($newlevel);


                $flashBagContent = "Le Niveau " . $levelTitle . " a bien été créé";
            }else {
                $flashBag = "error";
                $flashBagContent = "Le formulaire est invalide, veuillez le corriger.";
            }
            $this->get('session')->getFlashBag()->add(
                $flashBag,
                $flashBagContent
            );

            $result = $this->redirect($this->generateUrl('imagana_resources_creator_levels_list'));
        } else {
            $result = array(
                "tab" => "niveaux",
                "form"=>$form->createView(),
                "route" => "imagana_resources_creator_level_create",
                "previousRoute" => "imagana_resources_creator_levels_list"
            );
        }

        return $result;
    }

    /**
     * @Route(
     *     "/niveau/editer/{param}",
     *     name="imagana_resources_creator_level_edit",
     * )
     * @Method({"GET", "POST"})
     * @Template("ImaganaResourcesCreatorBundle::levelManaging.html.twig")
     *
     */
    public function levelEdit(Request $request , $param){
        $technicalName = $param;
        $formModel = new LevelModel() ;

        $dm = $this->container->get('doctrine_mongodb')->getManager();
        $levelsRepository = $dm->getRepository('ImaganaResourcesCreatorBundle:Level');

        $levelToUpdate = $levelsRepository->getLevelByTechnicalName($technicalName);

        $formModel->setTitle($levelToUpdate->getTitle());
        $formModel->setTechnicalName($technicalName);
        $formModel->setDescription($levelToUpdate->getDescription());
        $formModel->setTechnicalName($levelToUpdate->getTechnicalName());
        $formModel->setLevelCategory($levelToUpdate->getLevelCategory());
        $formModel->setMoreInformation($levelToUpdate->getMoreInformation());
        $formModel->setLevelWords($levelToUpdate-> getLevelWords());

        $formType = new LevelType();

        $form = $this->createForm($formType, $formModel);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            $flashBag = "notice";

            if ($form->isValid()) {
                $parameters = $request->request->all();

                // Recupération des Paramètres du Formulaires
                $levelTitle         = $parameters['imagana_resourcescreatorbundle_imaganaleveltype']['title'];
                $levelTechnicalName = $parameters['imagana_resourcescreatorbundle_imaganaleveltype']['technicalName'];
                $levelDescription   = $parameters['imagana_resourcescreatorbundle_imaganaleveltype']['description'];
                $levelWords         = $parameters['imagana_resourcescreatorbundle_imaganaleveltype']['levelWords'];
                $levelmoreInfo      = $parameters['imagana_resourcescreatorbundle_imaganaleveltype']['moreInformation'];
                $levelCategory      = $parameters['imagana_resourcescreatorbundle_imaganaleveltype']['levelCategory'];

                $levelToUpdate->setTitle($levelTitle);
                $levelToUpdate->setTechnicalName($levelTechnicalName);
                $levelToUpdate->setDescription($levelDescription);
                $levelToUpdate->setLevelWords($levelWords);
                $levelToUpdate->setLevelCategory( new \MongoId($levelCategory));
                $levelToUpdate->setMoreInformation($levelmoreInfo);

                $dm->persist($levelToUpdate);
                $dm->flush($levelToUpdate);

                $flashBagContent = "La niveau " . $technicalName . " a bien été mis à jour";
            } else {
                $flashBag = "error";
                $flashBagContent = "Le formulaire est invalide, veuillez le corriger.";
            }

            $this->get('session')->getFlashBag()->add(
                $flashBag,
                $flashBagContent
            );
        }

        $result = array(
            "tab" => "niveaux",
            "form"=>$form->createView(),
            "route" => "imagana_resources_creator_level_edit",
            "previousRoute" => "imagana_resources_creator_levels_list",
            "technicalName" => $technicalName
        );

        return $result ;
    }


    /**
     * @Route(
     *     "/niveau/liste/association/{paramResourceName}",
     *     name="imagana_resources_creator_levels_associated_resources"
     * )
     * @Method("GET")
     * @Template("ImaganaResourcesCreatorBundle::associatedResources.html.twig")
     */
    public function renderAssociatedResourcesAction($paramResourceName) {
        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {

            $levelTechnicalName = $paramResourceName;

            $dm = $this->container->get('doctrine_mongodb')->getManager();

            $levelsRepository = $dm->getRepository('ImaganaResourcesCreatorBundle:Level');
            $levelId = $levelsRepository->getLevelByTechnicalName($levelTechnicalName)->getId();

            $levelPedagogicalPurposeRepository = $dm->getRepository('ImaganaResourcesCreatorBundle:LevelPedagogicalPurpose');
            $associatedPedagogicalPurposesIdsArray = $levelPedagogicalPurposeRepository->getAllPedagogicalPurposeByLevelId(new \MongoId($levelId));

            $associatedPedagogicalPurposesArray = array();

            while($associatedPedagogicalPurposesIdsArray->hasNext()) {
                $apr = $associatedPedagogicalPurposesIdsArray->getNext();

                $associatedPedagogicalPurposesArray[] = $apr->getpedagogicalPurposeid();
            }

            $pedagogicalPurposeRepository = $dm->getRepository('ImaganaResourcesCreatorBundle:PedagogicalPurpose');

            if($associatedPedagogicalPurposesArray != null) {
                $associatedPedagogicalPurposes = $pedagogicalPurposeRepository->getAllPedagogicalPurposesByIdsArray($associatedPedagogicalPurposesArray);
            } else {
                $associatedPedagogicalPurposes = "";
            }

            $result = array(
                "associatedResources" => $associatedPedagogicalPurposes,
                "resourceTypeName" => "objectifs pédagogiques",
                "paramResourceName" => $paramResourceName,
                "associatorRoute" => "imagana_resources_creator_levels_associator"
            );

            return $result;
        }
    }



    /**
     * @Route(
     *     "/niveau/associer/{paramResourceName}/objectifs",
     *     name="imagana_resources_creator_levels_associator"
     * )
     * @Method({"GET", "POST"})
     * @Template("ImaganaResourcesCreatorBundle::associator.html.twig")
     *
     */
    public function levelAssociatorAction(Request $request, $paramResourceName) {

        $levelTechnicalName = $paramResourceName;

        $dm = $this->container->get('doctrine_mongodb')->getManager();

        $pedagogicalPurposeRepository = $dm->getRepository("ImaganaResourcesCreatorBundle:PedagogicalPurpose");

        $levelsRepository = $dm->getRepository('ImaganaResourcesCreatorBundle:Level');
        $levelId = $levelsRepository->getLevelByTechnicalName($levelTechnicalName)->getId();

        $levelPedagogicalPurposeRepository = $dm->getRepository('ImaganaResourcesCreatorBundle:LevelPedagogicalPurpose');

        if ($request->getMethod() == 'POST') {
            // Récupération des paramètres du formulaires
            $parameters = $request->request->all();

            $action = $parameters['formAction'];

            for($i=0;$i<count($parameters['formResources']);$i++) {
                // MongoId de l'objectif pédagogique à ajouter au niveau sélectionné
                $pedagogicalPurposeMongoId = new \MongoId($parameters['formResources'][$i]);

                if($action == "delete") {
                    $newLevelPedagogicalPurpose = $levelPedagogicalPurposeRepository->getPedagogicalPurposeByLevelIdAndPurposeId(new \MongoId($levelId), $pedagogicalPurposeMongoId);

                    $dm->remove($newLevelPedagogicalPurpose);
                    $dm->flush();
                } else {
                    $newLevelPedagogicalPurpose = new LevelPedagogicalPurpose();
                    $newLevelPedagogicalPurpose->setLevelId(new \MongoId($levelId));
                    $newLevelPedagogicalPurpose->setPedagogicalPurposeId($pedagogicalPurposeMongoId);

                    $dm->persist($newLevelPedagogicalPurpose);
                    $dm->flush($newLevelPedagogicalPurpose);
                }
            }
        }

        $associatedPedagogicalPurposesIds = $levelPedagogicalPurposeRepository->getAllPedagogicalPurposeByLevelId(new \MongoId($levelId));

        $associatedPedagogicalPurposesArray = array();

        while($associatedPedagogicalPurposesIds->hasNext()) {
            $apr = $associatedPedagogicalPurposesIds->getNext();

            $associatedPedagogicalPurposesArray[] = $apr->getpedagogicalPurposeid();
        }

        if($associatedPedagogicalPurposesArray != null) {
            $associatedPedagogicalPurposes = $pedagogicalPurposeRepository->getAllPedagogicalPurposesByIdsArray($associatedPedagogicalPurposesArray);

            $pedagogicalPurposeCursor = $pedagogicalPurposeRepository->getAllActivePedagogicalPurposesExcept($associatedPedagogicalPurposesArray);
        } else {
            $associatedPedagogicalPurposes = "";
            $pedagogicalPurposeCursor = $pedagogicalPurposeRepository->getAllActivePedagogicalPurposes();
        }

        $result = array(
            "route" => "imagana_resources_creator_levels_associator",
            "previousRoute" => "imagana_resources_creator_level_edit",
            "previousRouteParamName" => "param",
            "previousRouteParam" => $levelTechnicalName,
            "ressources" => "Objectifs pédagogiques",
            "availableResources" => $pedagogicalPurposeCursor,
            "associatedResources" => $associatedPedagogicalPurposes
        );

        return $result;
    }

    /**
     * @Route(
     *     "/niveau/supprimer/{paramResourceName}",
     *     name="imagana_resources_creator_levels_deletor"
     * )
     * @Method({"GET", "POST"})
     * @Template("ImaganaResourcesCreatorBundle::deletor.html.twig")
     *
     */
    public function levelDeletorAction(Request $request, $paramResourceName) {
        if ($request->getMethod() == 'POST') {
            $flashBag="notice" ;

            // Récupération des paramètres du formulaires
            $parameters = $request->request->all();

            $confirmInput = $parameters['deleteConfirm'];

            if($confirmInput == $paramResourceName) {
                $dm = $this->container->get('doctrine_mongodb')->getManager();
                $levelsRepository = $dm->getRepository('ImaganaResourcesCreatorBundle:Level');
                $levelToDelete = $levelsRepository->getLevelByTechnicalName($paramResourceName);

                if($levelToDelete != null) {
                    $levelToDelete->setIsactive(false);
                    $dm->persist($levelToDelete);
                    $dm->flush($levelToDelete);

                    $flashBagContent = "Le niveau \"" . $paramResourceName . "\" a bien été supprimé";
                } else {
                    $flashBag = "error";
                    $flashBagContent = "Le niveau \"" . $paramResourceName . "\" est introuvable";
                }
            } else {
                $flashBag = "error";
                $flashBagContent = "La saisie du champ de confirmation est incorrecte ! Veuillez recommencer.";
            }

            $this->get('session')->getFlashBag()->add(
                $flashBag,
                $flashBagContent
            );

            $result = $this->redirect($this->generateUrl('imagana_resources_creator_levels_list'));
        } else {
            $result = array(
                "previousRoute" => "imagana_resources_creator_level_edit",
                "previousRouteParam" => $paramResourceName,
            );

        }

        return $result;
    }


}
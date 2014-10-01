<?php

namespace Imagana\ResourcesCreatorBundle\Controller;

use Imagana\ResourcesCreatorBundle\Document\LevelModule;
use Imagana\ResourcesCreatorBundle\Document\Module;
use Imagana\ResourcesCreatorBundle\Form\ModuleType;
use Imagana\ResourcesCreatorBundle\FormModel\ModuleModel;
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

/*
 * Class MainController
 * @package Imagana\ResourcesCreatorBundle\Controller
 */

/**
 * @Route("admin/open/ImaganaResourcesCreator")
 */
class ModulesController extends Controller {

    /**
     * @Route(
     *     "/modules/{page}",
     *     name="imagana_resources_creator_modules_list",
     *     defaults={"page" = "1"},
     *     requirements={"page" = "\d+"},
     * )
     * @Method({"GET"})
     * @Template("ImaganaResourcesCreatorBundle::modulesManaging.html.twig")
     *
     */
    public function modulesListAction($page = 1) {

        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {

            $dm = $this->container->get('doctrine_mongodb')->getManager();
            $modulesRepo = $dm->getRepository("ImaganaResourcesCreatorBundle:Module");
            $modulesCursor = $modulesRepo->getAllActiveModules();

            $result = array(
                "tab" => "modules",
                "modules" => $modulesCursor
            );

            return $result;
        }
    }


    /**
     * @Route(
     *     "/module/creer",
     *     name="imagana_resources_creator_modules_create",
     * )
     * @Method({"GET", "POST"})
     * @Template("ImaganaResourcesCreatorBundle::categoryManaging.html.twig")
     *
     */
    public function modulesCreate(Request $request) {

        $formType = new ModuleType();
        $formModel = new ModuleModel();

        $form = $this->createForm($formType, $formModel);

        if($request->getMethod()=="POST"){
            $flashBag="notice" ;

            $form->handleRequest($request);
            if($form->isValid()){
                $dm = $this->container->get('doctrine_mongodb')->getManager();

                // Récupération des paramètres du formulaires
                $parameters = $request->request->all();
                $moduleTitle            = $parameters['imagana_resourcescreatorbundle_modulestype']['title'];
                $moduleSupport          = $parameters['imagana_resourcescreatorbundle_modulestype']['support'];
                $moduleDifficulties     = $parameters['imagana_resourcescreatorbundle_modulestype']['difficulties'];
                $modulesPedagogicalFlow = $parameters['imagana_resourcescreatorbundle_modulestype']['pedagogicalFlow'];
                $user = $this->container->get('security.context')->getToken()->getUser()->getUsername();

                $newModule = new Module();
                $newModule->setTitle($moduleTitle);
                $newModule->setSupport($moduleSupport);
                $newModule->setDifficulties($moduleDifficulties);
                $newModule->setPedagogicalFlow($modulesPedagogicalFlow);
                $newModule->setCreationDate(new \DateTime());
                $newModule->setCreator($user);
                $newModule->setIsActive(true);

                $dm->persist($newModule);
                $dm->flush($newModule);

                $flashBagContent = "Le module " . $moduleTitle . " a bien été créé";
            }else {
                $flashBag = "error";
                $flashBagContent = "Le formulaire est invalide, veuillez le corriger.";
            }
            $this->get('session')->getFlashBag()->add(
                $flashBag,
                $flashBagContent
            );

            $result = $this->redirect($this->generateUrl('imagana_resources_creator_modules_list'));
        } else {
            $result = array(
                "tab" => "modules",
                "form"=>$form->createView(),
                "route" => "imagana_resources_creator_modules_create",
                "previousRoute" => "imagana_resources_creator_modules_list"
            );
        }

        return $result;
    }


    /**
     * @Route(
     *     "/module/editer/{param}",
     *     name="imagana_resources_creator_module_edit",
     * )
     * @Method({"GET", "POST"})
     * @Template("ImaganaResourcesCreatorBundle::moduleManaging.html.twig")
     *
     */
    public function moduleEdit(Request $request, $param){

        $moduleTitle =$param;
        $formModel = new ModuleModel();

        $dm = $this->container->get('doctrine_mongodb')->getManager();
        $modulesRepository = $dm->getRepository('ImaganaResourcesCreatorBundle:Module');

        $moduleToUpdate = $modulesRepository->getModuleByTitle($moduleTitle);

        $formModel->setTitle($moduleToUpdate->getTitle());
        $formModel->setSupport($moduleToUpdate->getSupport());
        $formModel->setDifficulties($moduleToUpdate->getDifficulties());
        $formModel->setPedagogicalFlow($moduleToUpdate->getPedagogicalFlow());

        $formType = new ModuleType();

        $form = $this->createForm($formType, $formModel);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            $flashBag = "notice";
            $flashBagContent = "";

            if ($form->isValid()) {
                $parameters = $request->request->all();

                // Récupération des paramètres du formulaires
                $moduleTitle            = $parameters['imagana_resourcescreatorbundle_modulestype']['title'];
                $moduleSupport          = $parameters['imagana_resourcescreatorbundle_modulestype']['support'];
                $moduleDifficulties     = $parameters['imagana_resourcescreatorbundle_modulestype']['difficulties'];
                $modulesPedagogicalFlow = $parameters['imagana_resourcescreatorbundle_modulestype']['pedagogicalFlow'];

                $moduleToUpdate->setTitle($moduleTitle);
                $moduleToUpdate->setSupport($moduleSupport);
                $moduleToUpdate->setDifficulties($moduleDifficulties);
                $moduleToUpdate->setPedagogicalFlow($modulesPedagogicalFlow);

                $dm->persist($moduleToUpdate);
                $dm->flush($moduleToUpdate);
                $flashBagContent = "La module " . $moduleTitle . " a bien été mis à jour";
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
            "tab" => "modules",
            "form"=>$form->createView(),
            "route" => "imagana_resources_creator_module_edit",
            "previousRoute" => "imagana_resources_creator_modules_list",
            "moduleTitle" => $moduleTitle
        );

        return $result ;
    }

    /**
     * @Route(
     *     "/module/liste/association/{paramResourceName}",
     *     name="imagana_resources_creator_modules_associated_resources"
     * )
     * @Method("GET")
     * @Template("ImaganaResourcesCreatorBundle::associatedResources.html.twig")
     */
    public function renderAssociatedResourcesAction($paramResourceName) {
        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {

            $moduleName = $paramResourceName;

            // Récupération de l'entity manager
            $dm = $this->container->get('doctrine_mongodb')->getManager();


            // Récupération du repository des modules
            $moduleRepository = $dm->getRepository('ImaganaResourcesCreatorBundle:Module');

            // Récupération du moduleId en fonction du moduleName
            $moduleId = $moduleRepository->getModuleByTitle($moduleName)->getId();



            // Récupération du repository des levelmodule
            $levelModuleRepository = $dm->getRepository('ImaganaResourcesCreatorBundle:LevelModule');

            // Récupération des niveaux liés au module
            $associatedLevelsIdsCursor =  $levelModuleRepository->getAllLevelByModuleId(new \MongoId($moduleId));

            // Instantiation d'un tableau vide qui contiendra les ids des niveaux liés
            $associatedLevelsIdsArray = array();

            // Parcours du curseur mongo
            while($associatedLevelsIdsCursor->hasNext()) {
                // Récupère la valeur du niveau associé à ce stade d'itération
                $al = $associatedLevelsIdsCursor->getNext();

                // Ajoute l'id du niveau associé dans le tableau d'ids
                $associatedLevelsIdsArray[] = $al->getLevelId();
            }

            // Récupération du repository des levels
            $levelRepository = $dm->getRepository('ImaganaResourcesCreatorBundle:Level');

            // Vérifie que le tableau d'ids n'est pas vide
            if($associatedLevelsIdsArray != null) {
                $associatedLevels = $levelRepository->getAllLevelsByIdsArray($associatedLevelsIdsArray);
            } else {
                $associatedLevels = "";
            }



            // Tableau à renvoyer à la vue
            $result = array(
                "associatedResources" => $associatedLevels,
                "resourceTypeName" => "niveaux",
                "paramResourceName" => $paramResourceName,
                "associatorRoute" => "imagana_resources_creator_modules_associator"
            );

            // Retourne le tableau à la vue
            return $result;

        }
    }


    /**
     * @Route(
     *     "/module/associer/{paramResourceName}/niveaux",
     *     name="imagana_resources_creator_modules_associator"
     * )
     * @Method({"GET", "POST"})
     * @Template("ImaganaResourcesCreatorBundle::associator.html.twig")
     *
     */
    public function moduleAssociatorAction(Request $request, $paramResourceName) {

        // Récupère le nom du module depuis le slug de la route
        $moduleName = $paramResourceName;

        // Récupération de l'entity manager
        $dm = $this->container->get('doctrine_mongodb')->getManager();


        // Récupération du repository des modules
        $moduleRepository = $dm->getRepository('ImaganaResourcesCreatorBundle:Module');

        // Récupération du moduleId en fonction du moduleName
        $moduleId = $moduleRepository->getModuleByTitle($moduleName)->getId();

        // Récupération du repository des levelmodule
        $levelModuleRepository = $dm->getRepository('ImaganaResourcesCreatorBundle:LevelModule');

        // Vérifie si la méthode est POST
        if ($request->getMethod() == 'POST') {
            // Récupération des paramètres du formulaires
            $parameters = $request->request->all();

            // Récupération du paramètre action
            $action = $parameters['formAction'];


            // Parcours des ressources passées en paramètres
            for($i=0;$i<count($parameters['formResources']);$i++) {

                // MongoId du niveau à ajouter au module sélectionné
                $levelId = new \MongoId($parameters['formResources'][$i]);

                // Vérifie si l'action est supprimer
                if($action == "delete") {
                    // Récupère le document à supprimer par levelId et moduleId
                    $newLevelModule = $levelModuleRepository->getLevelModuleByLevelIdAndModuleId($levelId, new \MongoId($moduleId));

                    // Supprime le document
                    $dm->remove($newLevelModule);
                    // Flush
                    $dm->flush();
                } else {
                    // Créer un nouveau document LevelModule
                    $newLevelModule = new LevelModule();

                    // Set le levelId
                    $newLevelModule->setLevelId($levelId);

                    // Set le moduleId
                    $newLevelModule->setModuleId(new \MongoId($moduleId));

                    // Persiste le document
                    $dm->persist($newLevelModule);
                    // Flush
                    $dm->flush($newLevelModule);
                }
            }
        }


        // Récupération des niveaux liés au module
        $associatedLevelsIdsCursor =  $levelModuleRepository->getAllLevelByModuleId(new \MongoId($moduleId));

        // Instantiation d'un tableau vide qui contiendra les ids des niveaux liés
        $associatedLevelsIdsArray = array();

        // Parcours du curseur mongo
        while($associatedLevelsIdsCursor->hasNext()) {
            // Récupère la valeur du niveau associé à ce stade d'itération
            $aL = $associatedLevelsIdsCursor->getNext();

            // Ajoute l'id du niveau associé dans le tableau d'ids
            $associatedLevelsIdsArray[] = $aL->getLevelId();
        }

        // Récupération du repository des levels
        $levelRepository = $dm->getRepository('ImaganaResourcesCreatorBundle:Level');

        // Vérifie que le tableau d'ids n'est pas vide
        if($associatedLevelsIdsArray != null) {
            $associatedLevels = $levelRepository->getAllLevelsByIdsArray($associatedLevelsIdsArray);

            $levelCursor = $levelRepository->getAllActiveLevelsExcept($associatedLevelsIdsArray);
        } else {
            $associatedLevels = "";

            $levelCursor = $levelRepository->getAllActiveLevels();
        }

        // Tableau à renvoyer à la vue
        $result = array(
            "route" => "imagana_resources_creator_modules_associator",
            "previousRoute" => "imagana_resources_creator_module_edit",
            "previousRouteParamName" => "param",
            "previousRouteParam" => $moduleName,
            "ressources" => "Niveaux",
            "availableResources" => $levelCursor,
            "associatedResources" => $associatedLevels
        );

        // Retourne le tableau à la vue
        return $result;
    }

    /**
     * @Route(
     *     "/module/supprimer/{paramResourceName}",
     *     name="imagana_resources_creator_modules_deletor"
     * )
     * @Method({"GET", "POST"})
     * @Template("ImaganaResourcesCreatorBundle::deletor.html.twig")
     *
     */
    public function moduleDeletorAction(Request $request, $paramResourceName) {
        $result = array(
            "previousRoute" => "imagana_resources_creator_module_edit",
            "previousRouteParam" => $paramResourceName,
        );

        if ($request->getMethod() == 'POST') {
            $flashBag="notice" ;

            // Récupération des paramètres du formulaires
            $parameters = $request->request->all();

            $confirmInput = $parameters['deleteConfirm'];

            if($confirmInput == $paramResourceName) {
                $dm = $this->container->get('doctrine_mongodb')->getManager();
                $modulesRepository = $dm->getRepository('ImaganaResourcesCreatorBundle:Module');
                $moduleToDelete = $modulesRepository->getModuleByTitle($paramResourceName);

                if($moduleToDelete != null) {
                    $moduleToDelete->setIsactive(false);
                    $dm->persist($moduleToDelete);
                    $dm->flush($moduleToDelete);

                    $flashBagContent = "Le module \"" . $paramResourceName . "\" a bien été supprimé";
                    $result = $this->redirect($this->generateUrl('imagana_resources_creator_modules_list'));
                } else {
                    $flashBag = "error";
                    $flashBagContent = "Le module \"" . $paramResourceName . "\" est introuvable";
                }
            } else {
                $flashBag = "error";
                $flashBagContent = "La saisie du champ de confirmation est incorrecte ! Veuillez recommencer.";
            }

            $this->get('session')->getFlashBag()->add(
                $flashBag,
                $flashBagContent
            );
        }

        return $result;
    }

}
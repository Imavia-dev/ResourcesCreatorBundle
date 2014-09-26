<?php

namespace Imagana\ResourcesCreatorBundle\Controller;

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

        }

        $result = array(
            "tab" => "modules",
            "form"=>$form->createView(),
            "route" => "imagana_resources_creator_modules_create",
            "previousRoute" => "imagana_resources_creator_modules_list"
        );

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
     *     "/module/associer/{param}/niveaux",
     *     name="imagana_resources_creator_modules_associator"
     * )
     * @Method({"GET"})
     * @Template("ImaganaResourcesCreatorBundle::associator.html.twig")
     *
     */
    public function moduleAssociatorAction(Request $request, $param) {
        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {

            $moduleName = $param;

            $associatedResources = "niveaux";

            $dm = $this->container->get('doctrine_mongodb')->getManager();
            $levelsRepo = $dm->getRepository("ImaganaResourcesCreatorBundle:Level");

            $levelsCursor = $levelsRepo->getAllActiveLevels();

            if ($request->getMethod() == 'POST') {

            }

            $result = array(
                "route" => "imagana_resources_creator_modules_associator",
                "previousRoute" => "imagana_resources_creator_module_edit",
                "previousRouteParamName" => "moduleTitle",
                "previousRouteParam" => $moduleName,
                "ressources" => $associatedResources,
                "availableResources" => $levelsCursor
            );

            return $result;
        }
    }

}
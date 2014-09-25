<?php

namespace Imagana\ResourcesCreatorBundle\Controller;

use Claroline\CoreBundle\Manager\RoleManager;
use Claroline\CoreBundle\Manager\UserManager;
use Imagana\ResourcesCreatorBundle\Document\Level;
use JMS\Serializer\Tests\Serializer\DateIntervalFormatTest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\ValidatorInterface;
use Symfony\Component\Routing\RequestContext;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Adapter\MongoAdapter;
use Pagerfanta\Adapter\DoctrineODMMongoDBAdapter;

use Claroline\CoreBundle\Library\Configuration\PlatformConfigurationHandler;

use Claroline\CoreBundle\Persistence\ObjectManager;

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

    private $usermanager;
    private $objectManager;
    private $userRepo;
    private $validator;
    private $configHandler;

    /**
     * Constructor.
     *
     * @DI\InjectParams({
     *     "um"            = @DI\Inject("claroline.manager.user_manager"),
     *     "rm"            = @DI\Inject("claroline.manager.role_manager"),
     *     "objectManager" = @DI\Inject("claroline.persistence.object_manager"),
     *     "validator"     = @DI\Inject("validator"),
     *     "configHandler" = @DI\Inject("claroline.config.platform_config_handler"),
     * })
     */
    public function __construct(
        UserManager $um,
        RoleManager $rm,
        ObjectManager $objectManager,
        ValidatorInterface $validator,
        PlatformConfigurationHandler $configHandler
    ) {
        $this->usermanager = $um;
        $this->rolemanager = $rm;
        $this->userRepo = $objectManager->getRepository('ClarolineCoreBundle:User');
        $this->objectManager = $objectManager;
        $this->validator = $validator;
        $this->configHandler = $configHandler;
    }

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

         
            // @TODO repository function to list all niveaux ordered by name

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

                $dm         = $this->container->get('doctrine_mongodb')->getManager();
                $categoriesRepository = $dm->getRepository('ImaganaResourcesCreatorBundle:LevelCategory');
                $parameters = $request->request->all();

                // Recupération des Paramètres du Formulaires
                $levelTitle         = $parameters['imagana_resourcescreatorbundle_imaganaleveltype']['title'];
                $levelTechnicalName = $parameters['imagana_resourcescreatorbundle_imaganaleveltype']['technicalName'];
                $levelDescription   = $parameters['imagana_resourcescreatorbundle_imaganaleveltype']['description'];
                $levelWords         = $parameters['imagana_resourcescreatorbundle_imaganaleveltype']['levelWords'];
                $levelmoreInfo      = $parameters['imagana_resourcescreatorbundle_imaganaleveltype']['moreInformation'];
                $levelCategory      = $parameters['imagana_resourcescreatorbundle_imaganaleveltype']['levelCategory'];
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


                $flashBagContent = "Le Niveau " . $levelTitle . " a bien été créée";
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
            "tab" => "niveaux",
            "form"=>$form->createView(),
            "route" => "imagana_resources_creator_level_create",
            "previousRoute" => "imagana_resources_creator_levels_list"
        );


        return $result;
    }

    /**
     * @Route(
     *     "/niveau/editer/{technicalName}",
     *     name="imagana_resources_creator_level_edit",
     * )
     * @Method({"GET", "POST"})
     * @Template("ImaganaResourcesCreatorBundle::levelManaging.html.twig")
     *
     */
    public function levelEdit(Request $request , $technicalName){
        $formModel = new LevelModel() ;

        $dm = $this->container->get('doctrine_mongodb')->getManager();
        $levelRepository = $dm->getRepository('ImaganaResourcesCreatorBundle:Level');


        $levelsToUpdate = $levelRepository->getLevelByTechnicalName($technicalName);



        $formModel->setTitle($levelsToUpdate->getTitle());
        $formModel->setTechnicalName($technicalName);
        $formModel->setDescription($levelsToUpdate->getDescription());
        $formModel->setTechnicalName($levelsToUpdate->getTechnicalName());
        $formModel->setLevelCategory($levelsToUpdate->getLevelCategory());
        $formModel->setMoreInformation($levelsToUpdate->getMoreInformation());
        $formModel->setLevelWords($levelsToUpdate-> getLevelWords());

        $formType = new LevelType();

        $form = $this->createForm($formType, $formModel);

        if ($request->getMethod() == 'POST') {

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

}
<?php

namespace Imagana\ResourcesCreatorBundle\Controller;

use Claroline\CoreBundle\Manager\RoleManager;
use Claroline\CoreBundle\Manager\UserManager;
use Imagana\AccountsManagerBundle\Document\PlayersDirectory;
use Imagana\ResourcesCreatorBundle\Form\ModuleLevelsType;
use Imagana\ResourcesCreatorBundle\Form\ModulesType;
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
use Claroline\CoreBundle\Entity\User;
use Claroline\CoreBundle\Persistence\ObjectManager;

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
     *     "/modules/{page}",
     *     name="imagana_resources_creator_modules_list",
     *     defaults={"page" = "1"},
     *     requirements={"page" = "\d+"},
     * )
     * @Method({"GET"})
     * @Template("ImaganaResourcesCreatorBundle::levelsManaging.html.twig")
     *
     */
    public function modulesListAction($page = 1) {

        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {

            // @TODO repository function to list all niveaux ordered by name

            $result = array(
                "tab" => "modules",
                "modules" => ""
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

        $formType = new ModulesType();

        $form = $this->createForm($formType);

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
     *     "/module/associer/{moduleName}/{associatedResources}",
     *     name="imagana_resources_creator_modules_associator"
     * )
     * @Method({"GET"})
     * @Template("ImaganaResourcesCreatorBundle::associator.html.twig")
     *
     */
    public function moduleAssociatorAction($moduleName, $associatedResources) {

        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {

            $result = array(
                "route" => "imagana_resources_creator_modules_associator",
                "previousRoute" => "imagana_resources_creator_modules_list",
                "ressources" => $associatedResources,
                ""

            );

            return $result;
        }
    }

}
<?php

namespace Imagana\ResourcesCreatorBundle\Controller;

use Claroline\CoreBundle\Manager\RoleManager;
use Claroline\CoreBundle\Manager\UserManager;
use Imagana\AccountsManagerBundle\Document\PlayersDirectory;
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

use Imagana\AccountsManagerBundle\FormModel\imaganaUserModel;
use Imagana\AccountsManagerBundle\Form\imaganaUserType;

use Claroline\CoreBundle\Library\Configuration\PlatformConfigurationHandler;
use Claroline\CoreBundle\Entity\User;
use Claroline\CoreBundle\Persistence\ObjectManager;

use JMS\DiExtraBundle\Annotation as DI;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Imagana\ResourcesCreatorBundle\FormModel\imaganaCategoryModel;
use Imagana\ResourcesCreatorBundle\Form\imaganaCategoryType;

/*
 * Class MainController
 * @package Imagana\ResourcesCreatorBundle\Controller
 */

/**
 * @Route("admin/open/ImaganaResourcesCreator")
 */
class CategoriesController extends Controller {

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
     *     "/categories/{page}",
     *     name="imagana_resources_creator_categories_list",
     *     defaults={"page" = "1"},
     *     requirements={"page" = "\d+"},
     * )
     * @Method({"GET"})
     * @Template("ImaganaResourcesCreatorBundle::categoriesManaging.html.twig")
     *
     */
    public function categoriesListAction($page = 1) {

        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {

            $result = array(
                "tab" => "categories"
            );

            return $result;
        }
    }

    /**
     * @Route(
     *     "/categorie/{categoryName}",
     *     name="imagana_resources_creator_category_management",
     *     defaults={"categoryName" = "create"},
     * )
     * @Method({"GET", "POST"})
     * @Template("ImaganaResourcesCreatorBundle::levelManaging.html.twig")
     *
     */
    public function categoryManaging(Request $request, $categoryName) {
        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {

            $formModel = new imaganaCategoryModel();
            $formType = new imaganaCategoryType();

            $form = $this->createForm($formType, $formModel);

            // Permet de réafficher un formulaire vide
            $clearForm = clone $form;

            if ($request->getMethod() == 'POST') {
                $form->handleRequest($request);
                if ($form->isValid()) {

                }
            }

            $result = array(
                "tab" => "categories",
                "form"=>$form->createView()
            );

            return $result;
        }
    }

}
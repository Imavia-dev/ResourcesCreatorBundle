<?php

namespace Imagana\ResourcesCreatorBundle\Controller;

use Claroline\CoreBundle\Manager\RoleManager;
use Claroline\CoreBundle\Manager\UserManager;
use Entity\Category;
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
use Imagana\ResourcesCreatorBundle\Document\LevelCategory;

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

            // @TODO repository function to list all categories ordered by name

            $result = array(
                "tab" => "categories",
                "categories" => ""
            );

            return $result;
        }
    }

    /**
     * @Route(
     *     "/categorie/creer",
     *     name="imagana_resources_creator_category_create",
     * )
     * @Method({"GET", "POST"})
     * @Template("ImaganaResourcesCreatorBundle::categoryManaging.html.twig")
     *
     */
    public function categoryCreate(Request $request) {
        $formModel = new imaganaCategoryModel();
        $formType = new imaganaCategoryType();

        $form = $this->createForm($formType, $formModel);

        if ($request->getMethod() == 'POST') {

            $flashBag = "notice";
            $flashBagContent = "";

            $form->handleRequest($request);
            if ($form->isValid()) {
                //$dm = $this->container->get('doctrine_mongodb')->getManager();

                $parameters = $request->request->all();

                $categoryDescription = $parameters['imagana_resourcescreatorbundle_imaganacategorytype']['description'];

                //$user = $this->container->get('security.context')->getToken()->getUser();

                /*$newCategory = new LevelCategory();
                $newCategory->setDescription($categoryDescription);
                $newCategory->setCreator($user);
                $newCategory->setCreationDate(new \DateTime());

                $dm->persist($newCategory);
                $dm->flush($newCategory);*/

                $flashBagContent = "La catégorie " . $categoryDescription . " a bien été créée";
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
            "tab" => "categories",
            "form"=>$form->createView(),
            "route" => "imagana_resources_creator_category_create",
            "previousRoute" => "imagana_resources_creator_categories_list"
        );

        return $result;
    }

    /**
     * @Route(
     *     "/categorie/editer/{categoryName}",
     *     name="imagana_resources_creator_category_edit",
     * )
     * @Method({"GET", "POST"})
     * @Template("ImaganaResourcesCreatorBundle::categoryManaging.html.twig")
     *
     */
    public function categoryEdit(Request $request, $categoryName) {
        $formModel = new imaganaCategoryModel();
        $formType = new imaganaCategoryType();

        $form = $this->createForm($formType, $formModel);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {

            }
        }

        $result = array(
            "tab" => "categories",
            "form"=>$form->createView(),
            "route" => "imagana_resources_creator_category_edit",
            "categoryName" => $categoryName,
            "previousRoute" => "imagana_resources_creator_categories_list"
        );

        return $result;
    }

    /**
     * @Route(
     *     "/categorie/supprimer/{categoryName}",
     *     name="imagana_resources_creator_category_delete",
     * )
     * @Method({"GET", "POST"})
     * @Template("ImaganaResourcesCreatorBundle::categoryManaging.html.twig")
     *
     */
    public function categoryDelete(Request $request, $categoryName) {
    }

}
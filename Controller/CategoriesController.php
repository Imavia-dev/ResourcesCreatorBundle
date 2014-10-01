<?php

namespace Imagana\ResourcesCreatorBundle\Controller;


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
use Imagana\ResourcesCreatorBundle\FormModel\CategoryModel;
use Imagana\ResourcesCreatorBundle\Form\CategoryType;
use Imagana\ResourcesCreatorBundle\Document\LevelCategory;

/*
 * Class MainController
 * @package Imagana\ResourcesCreatorBundle\Controller
 */

/**
 * @Route("admin/open/ImaganaResourcesCreator")
 */
class CategoriesController extends Controller {


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

            $dm = $this->container->get('doctrine_mongodb')->getManager();
            $categoriesRepository = $dm->getRepository('ImaganaResourcesCreatorBundle:LevelCategory');


            // Get list of all active categories
            $categories = $categoriesRepository->getAllActivesCategories();





            // @TODO repository function to list all categories ordered by name

            $result = array(
                "tab" => "categories",
                "categories" => $categories
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
        $formModel = new CategoryModel();
        $formType = new CategoryType();

        $form = $this->createForm($formType, $formModel);

        if ($request->getMethod() == 'POST') {

            $flashBag = "notice";

            $form->handleRequest($request);
            if ($form->isValid()) {
                $dm = $this->container->get('doctrine_mongodb')->getManager();

                $parameters = $request->request->all();

                $categoryDescription = $parameters['imagana_resourcescreatorbundle_imaganacategorytype']['description'];

                $user = $this->container->get('security.context')->getToken()->getUser()->getUsername();

                $newCategory = new LevelCategory();
                $newCategory->setDescription($categoryDescription);
                $newCategory->setCreator($user);
                $newCategory->setCreationDate(new \DateTime());
                $newCategory->setIsActive(true);

                $dm->persist($newCategory);
                $dm->flush($newCategory);

                $flashBagContent = "La catégorie " . $categoryDescription . " a bien été créée";
            } else {
                $flashBag = "error";
                $flashBagContent = "Le formulaire est invalide, veuillez le corriger.";
            }

            $this->get('session')->getFlashBag()->add(
                $flashBag,
                $flashBagContent
            );

            $result = $this->redirect($this->generateUrl('imagana_resources_creator_categories_list'));
        } else {
            $result = array(
                "tab" => "categories",
                "form"=>$form->createView(),
                "route" => "imagana_resources_creator_category_create",
                "previousRoute" => "imagana_resources_creator_categories_list"
            );
        }

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
        $formModel = new CategoryModel();

        $formModel->setDescription($categoryName);
        $formType = new CategoryType();

        $form = $this->createForm($formType, $formModel);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            $flashBag = "notice";
            $flashBagContent = "";

            if ($form->isValid()) {
                $dm = $this->container->get('doctrine_mongodb')->getManager();
                $categoriesRepository = $dm->getRepository('ImaganaResourcesCreatorBundle:LevelCategory');

                $parameters = $request->request->all();


                $categoryDescription = $parameters['imagana_resourcescreatorbundle_imaganacategorytype']['description'];
                $categoryToEdit =  $categoriesRepository->getCategoriesByDescription($categoryName);
                $categoryToEdit->setDescription($categoryDescription);

                $dm->persist($categoryToEdit);
                $dm->flush($categoryToEdit);

                $flashBagContent = "La catégorie " . $categoryDescription . " a bien été mise à jour";
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
            "route" => "imagana_resources_creator_category_edit",
            "categoryName" => $categoryName,
            "previousRoute" => "imagana_resources_creator_categories_list"
        );

        return $result;
    }

    /**
     * @Route(
     *     "/categorie/supprimer/{paramResourceName}",
     *     name="imagana_resources_creator_categories_deletor"
     * )
     * @Method({"GET", "POST"})
     * @Template("ImaganaResourcesCreatorBundle::deletor.html.twig")
     *
     */
    public function levelCategoryDeletorAction(Request $request, $paramResourceName) {

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
                $levelCategoryRepository = $dm->getRepository('ImaganaResourcesCreatorBundle:LevelCategory');
                $levelCategoryToDelete = $levelCategoryRepository->getCategoryByDescription($paramResourceName);

                if($levelCategoryToDelete != null) {
                    $levelCategoryToDelete->setIsactive(false);
                    $dm->persist($levelCategoryToDelete);
                    $dm->flush($levelCategoryToDelete);

                    $flashBagContent = "Le niveau \"" . $paramResourceName . "\" a bien été supprimé";
                    $result = $this->redirect($this->generateUrl('imagana_resources_creator_categories_list'));
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
        }

        return $result;
    }


}
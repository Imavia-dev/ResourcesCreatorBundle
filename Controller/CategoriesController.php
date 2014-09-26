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
     *     "/categorie/supprimer/{categoryName}",
     *     name="imagana_resources_creator_category_delete",
     * )
     * @Method({"GET", "POST"})
     * @Template("ImaganaResourcesCreatorBundle::categoryManaging.html.twig")
     *
     */
    public function categoryDelete(Request $request, $categoryName) {

        // @TODO repository function to retrieve the levelCategory
        // $categoryToDelete = ;


        // @TODO reository function edit all levels with categoryName to ""
    }

}
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
class PedagogicalPurposeController extends Controller {


}
<?php

/**
*
* @author Jerome Varini <jerome.varini@Imagana.fr>
* @author Fricker Sebastien <sebastien.fricker@Imagana.fr>
* @link http://www.Imagana.fr Site web Imagana
*
*/

namespace Imagana\ResourcesCreatorBundle\Listener;


use Symfony\Component\DependencyInjection\ContainerAware;
use Claroline\CoreBundle\Event\DisplayToolEvent;
use Claroline\CoreBundle\Event\OpenAdministrationToolEvent;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\HttpKernelInterface;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
* Listener Resources Creator
 * @DI\Service()
*/
class ResourcesCreatorListener extends ContainerAware
{
    protected $container ;

    /**
     * @DI\InjectParams({
     *     "container"      = @DI\Inject("service_container"),
     *     "requestStack"   = @DI\Inject("request_stack"),
     *     "httpKernel"     = @DI\Inject("http_kernel")
     * })
     */
    public function __construct ($container, RequestStack $requestStack, HttpKernelInterface $httpKernel){
        $this->container=$container ;
        $this->request = $requestStack->getCurrentRequest();
        $this->httpKernel = $httpKernel;
    }

    /**
     * function onDisplay
     * est apppelee par le listeners
     * Evenement declenché par le workspace claroline
     *
     * @DI\Observe("administration_tool_ImaganaResourcesCreator")
     */
    public function onAdministrationOpen(OpenAdministrationToolEvent $event) {
        $params = array('page'=>1);
        $params['_controller'] = 'ImaganaResourcesCreatorBundle:Levels:levelsList';
        $subRequest = $this->request->duplicate(array(), null, $params);
        $response = $this->httpKernel->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
        $event->setResponse($response);
    }

}
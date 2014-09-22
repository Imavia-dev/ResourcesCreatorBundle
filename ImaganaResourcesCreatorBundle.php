<?php
/**
* Bundle Imagana ResourcesCreatorBundle
*
* @author Jerome Varini <jerome.varini@Imagana.fr>
* @author Fricker Sebastien <sebastien.fricker@Imagana.fr>
* @link http://www.Imagana.fr Site web Imagana
*
*/
namespace Imagana\ResourcesCreatorBundle;

use Claroline\CoreBundle\Library\PluginBundle;
use Claroline\KernelBundle\Bundle\ConfigurationBuilder;

/*
* Imagana Accounts Manager Bundle
*/

class ImaganaResourcesCreatorBundle extends PluginBundle
{
  
    public function hasMigrations()
    {
        return false;
    }
    
    public function getConfiguration($environment)
    {
        $config = new ConfigurationBuilder();

        return $config->addRoutingResource(__DIR__ . '/Resources/config/routing.yml', null, '');
    }

    public function suggestConfigurationFor(Bundle $bundle, $environment)
    {
        if ($bundle instanceof \Doctrine\Bundle\MongoDBBundle\DoctrineMongoDBBundle ){
            $config = new ConfigurationBuilder();
            $config->addContainerResource(
                __DIR__ . '/Resources/config/dbconfig.yml'
            );

            return $config;
        }
    }

}


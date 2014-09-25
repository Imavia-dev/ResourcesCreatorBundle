<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 22/09/14
 * Time: 16:03
 * User: jerome
 * Date: 24/09/14
 * Time: 10:06
 */

namespace Imagana\ResourcesCreatorBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

class ModulesRepository extends DocumentRepository {

    public function getAllActiveModules()
    {
        return $this->createQueryBuilder()->field('isActive')->equals(true)->getQuery()->execute();
    }

    public function getModuleByTitle($moduleTitle)
    {
        return $this->createQueryBuilder()->field('title')->equals($moduleTitle)->getQuery()->getSingleResult();
    }

} 
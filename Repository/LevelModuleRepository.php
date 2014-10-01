<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 22/09/14
 * Time: 16:03
 */

namespace Imagana\ResourcesCreatorBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;


class LevelModuleRepository  extends DocumentRepository{

    public function getAllLevelByModuleId($moduleId) {
        return $this->createQueryBuilder()->field('moduleId')->equals($moduleId)->getQuery()->execute();
    }

    public function getLevelModuleByLevelIdAndModuleId($levelId, $moduleId) {
        return $this->createQueryBuilder()->field('levelId')->equals($levelId)->field('moduleId')->equals($moduleId)->getQuery()->getSingleResult();
    }

}
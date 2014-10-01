<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 22/09/14
 * Time: 16:03
 */

namespace Imagana\ResourcesCreatorBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;


class LevelRepository  extends DocumentRepository{

    public function getAllActiveLevels()
    {
        return $this->createQueryBuilder()->field('isActive')->equals(true)->getQuery()->execute();
    }

    public function getAllActiveLevelsExcept($exceptionIdsArray)
    {
        return $this->createQueryBuilder()->field('_id')->notIn($exceptionIdsArray)->getQuery()->execute();
    }

    public function getLevelByTechnicalName($technicalName)
    {
        return $this->createQueryBuilder()->field('technicalName')->equals($technicalName)->getQuery()->getSingleResult();
    }

    public function getAllLevelsByIdsArray($idsArray) {
        return $this->createQueryBuilder()->field('_id')->in($idsArray)->getQuery()->execute();
    }

}
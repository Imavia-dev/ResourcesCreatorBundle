<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 22/09/14
 * Time: 16:03
 */

namespace Imagana\ResourcesCreatorBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;


class LevelPedagogicalPurposeRepository  extends DocumentRepository{

    public function getAllPedagogicalPurposeByLevelId($levelId) {
        return $this->createQueryBuilder()->field('levelId')->equals($levelId)->getQuery()->execute();
    }

    public function getPedagogicalPurposeByLevelIdAndPurposeId($levelId, $purposeId) {
        return $this->createQueryBuilder()->field('levelId')->equals($levelId)->field('pedagogicalPurposeId')->equals($purposeId)->getQuery()->getSingleResult();
    }

}
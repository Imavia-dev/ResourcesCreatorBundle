<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 22/09/14
 * Time: 16:03
 */

namespace Imagana\ResourcesCreatorBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;


class PedagogicalPurposeRepository  extends DocumentRepository{

    public function getAllActivePedagogicalPurposes()
    {
        return $this->createQueryBuilder()->field('isActive')->equals(true)->getQuery()->execute();
    }

    public function getPedagogicalPurposeByDescription($PedagogicalPurposesDescription)
    {
        return $this->createQueryBuilder()->field('description')->equals($PedagogicalPurposesDescription)->getQuery()->getSingleResult();
    }

    public function getPedagogicalPurposeById($PedagogicalPurposeId) {
        return $this->createQueryBuilder()->field('_id')->equals($PedagogicalPurposeId)->getQuery()->getSingleResult();
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 22/09/14
 * Time: 16:03
 */

namespace Imagana\ResourcesCreatorBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;


class LevelCategoryRepository  extends DocumentRepository{

    public function getAllActivesCategories()
    {
        return $this->createQueryBuilder()->field('isActive')->equals(true)->getQuery()->execute();

    }

    public function getCategoryByDescription($description)
    {
        return $this->createQueryBuilder()->field('description')->equals($description)->getQuery()->getSingleResult();

    }



}
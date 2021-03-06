<?php

namespace App\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ApplicationRepository
 *
 * This class was generated by the PhpStorm "Php Annotations" Plugin. Add your own custom
 * repository methods below.
 */
class ApplicationRepository extends EntityRepository
{
    public function persist(Application $application)
    {
        $em = $this->getEntityManager();
        $em->persist($application);
        $em->flush();
    }
}

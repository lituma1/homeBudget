<?php

namespace HomeBudget\HomeBudgetBundle\Repository;

use Doctrine\ORM\EntityRepository;
use HomeBudget\HomeBudgetBundle\Entity\Account;

/**
 * AccountRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AccountRepository extends EntityRepository
{
    public function queryOwnedBy($user) {

        $query = $this->createQueryBuilder("a")
                ->where('a.user = :user')
                ->andWhere('a.status = 1')
                ->orderBy('a.name', 'ASC')
                ->setParameter('user', $user);

        return $query;
    }
     public function queryOwnedByWithoutThisAccount($user, $id) {

        $query = $this->createQueryBuilder("a")
                ->where('a.user = :user')
                ->andWhere('a.status = 1')
                ->andWhere("a.id != $id")
                ->orderBy('a.name', 'ASC')
                ->setParameter('user', $user);

        return $query;
    }
    public function findByUserAndStatus($user){
        
        $expends = $this->getEntityManager()->createQuery(
                        'SELECT a FROM HBBundle:Account a '
                . 'WHERE a.user = :User AND a.status = 1 ORDER BY a.name ASC')
                ->setParameter("User", $user)
                ->getResult();
        return $expends;
        
    }
}

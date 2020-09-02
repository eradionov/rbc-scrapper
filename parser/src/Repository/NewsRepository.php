<?php

namespace App\Repository;

use App\Entity\News;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method News|null find($id, $lockMode = null, $lockVersion = null)
 * @method News|null findOneBy(array $criteria, array $orderBy = null)
 * @method News[]    findAll()
 * @method News[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, News::class);
    }

    /**
     * @param array $externalKeys
     *
     * @return array
     */
    public function findExistingExternalIdsByExternalKeys(array $externalKeys): array
    {
        $articleExternalIds = $this->createQueryBuilder('ns')
            ->select('ns.externalId')
            ->andWhere('ns.externalId IN(:externalIds)')
            ->setParameter('externalIds', $externalKeys)
            ->orderBy('ns.id', 'DESC')
            ->getQuery()
            ->getScalarResult();

        return array_column($articleExternalIds, 'externalId');
    }
}

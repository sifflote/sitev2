<?php

namespace App\Repository\Forum;

use App\Entity\Forum\Tag;
use App\Entity\Forum\Topic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

class TopicRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Topic::class);
    }

    public function queryAllForTag(?Tag $tag): Query
    {
        $query = $this->createQueryBuilder('t')
            ->setMaxResults(20)
            ->orderBy('t.createdAt', 'DESC');
        if ($tag) {
            $tags = [$tag];
            if ($tag->getChildren()->count() > 0) {
                $tags = $tag->getChildren()->toArray();
            }
            $query
                ->join('t.tags', 'tag')
                ->where('tag IN (:tags)')
                ->setParameter('tags', $tags);
        }
        return $query->getQuery();
    }

    public function findAllBatched(): iterable
    {
        $limit = 0;
        $perPage = 1000;
        while(true) {
            $rows = $this->createQueryBuilder('t')
                ->setMaxResults($perPage)
                ->setFirstResult($limit)
                ->getQuery()
                ->getResult()
            ;
            if (empty($rows)) {
                break;
            }
            foreach($rows as $row) {
                yield $row;
            }
            $limit += $perPage;
            $this->getEntityManager()->clear();
        }
    }

}
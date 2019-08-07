<?php

namespace FroshWebP\Repositories;

use Doctrine\ORM\QueryBuilder;
use Shopware\Models\Media\Media;
use Shopware\Models\Media\Repository;

/**
 * Class WebPMediaRepository
 */
class WebPMediaRepository extends Repository
{
    /**
     * @return QueryBuilder
     */
    public function findImages(): QueryBuilder
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('media')
            ->from(Media::class, 'media')
            ->where('media.type = :type')
            ->setParameter(':type', Media::TYPE_IMAGE)
            ->andWhere('media.albumId != -13'); // trashbinID
    }

    /**
     * @param array $useCollections
     * @param array $ignoreCollections
     * @return int
     */
    public function countMedias($useCollections = [], $ignoreCollections = []): int
    {
        $medias = $this->findImages();
        if (!empty($useCollections)) {
            $and_cond = $medias->expr()->orX();
            foreach ($useCollections as $collection) {
                $and_cond->add($medias->expr()->eq('media.albumId', $medias->expr()->literal($collection)));
            }
            $medias->andWhere($and_cond);
        }
        if (!empty($ignoreCollections)) {
            $and_cond = $medias->expr()->orX();
            foreach ($ignoreCollections as $collection) {
                $and_cond->add($medias->expr()->neq('media.albumId', $medias->expr()->literal($collection)));
            }
            $medias->andWhere($and_cond);
        }

        return count($medias->getQuery()->getArrayResult());
    }

    /**
     * @param int $stack
     * @param int $offset
     *
     * @param array $useCollections
     * @param array $ignoreCollections
     * @return mixed
     */
    public function findByOffset($stack, $offset, $useCollections = [], $ignoreCollections = [])
    {
        $medias = $this->findImages();
        if (!empty($useCollections)) {
            $and_cond = $medias->expr()->orX();
            foreach ($useCollections as $collection) {
                $and_cond->add($medias->expr()->eq('media.albumId', $medias->expr()->literal($collection)));
            }
            $medias->andWhere($and_cond);
        }
        if (!empty($ignoreCollections)) {
            $and_cond = $medias->expr()->orX();
            foreach ($ignoreCollections as $collection) {
                $and_cond->add($medias->expr()->neq('media.albumId', $medias->expr()->literal($collection)));
            }
            $medias->andWhere($and_cond);
        }

        return $medias
            ->setFirstResult($offset)
            ->setMaxResults($stack)
            ->getQuery()->getArrayResult();
    }
}

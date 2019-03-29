<?php

namespace FroshWebP\Repositories;

use Shopware\Models\Media\Media;
use Shopware\Models\Media\Repository;

/**
 * Class WebPMediaRepository
 */
class WebPMediaRepository extends Repository
{
    /**
     * @return int
     */
    public function countMedias()
    {
        $medias = $this->getEntityManager()->createQueryBuilder()
            ->select('media')
            ->from(Media::class, 'media')
            ->where('media.type = :type')
            ->setParameter(':type', Media::TYPE_IMAGE)
            ->getQuery()->getArrayResult();
        return count($medias);
    }

    /**
     * @param int $stack
     * @param int $offset
     *
     * @return mixed
     */
    public function findByOffset(int $stack, int $offset)
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('media')
            ->from(Media::class, 'media')
            ->where('media.type = :type')
            ->setParameter(':type', Media::TYPE_IMAGE)
            ->setFirstResult($offset)
            ->setMaxResults($stack)
            ->getQuery()->getArrayResult();
    }
}

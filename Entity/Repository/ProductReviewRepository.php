<?php


namespace Smile\Bundle\ProductReviewBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Smile\Bundle\ProductReviewBundle\Entity\ProductReview;

/**
 * Class ProductReviewRepository
 */
class ProductReviewRepository extends EntityRepository
{
    /**
     * @param int $productId
     * @param string $status
     *
     * @return ProductReview[]
     */
    public function getReviewsByProductIdAndStatus(
        int $productId,
        string $status = ProductReview::STATUS_PUBLISHED
    ): array {
        $qb = $this->getQueryBuilder();

        return $this->injectProductIdAndStatusConditions($qb, $productId, $status)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param int $productId
     * @param string $status
     *
     * @return int
     */
    public function getCountReviewsByProductIdAndStatus(
        int $productId,
        string $status = ProductReview::STATUS_PUBLISHED
    ) {
        $qb = $this->getQueryBuilder()
            ->select('count(product_review.id)')
        ;

        try {
            return (int) $this->injectProductIdAndStatusConditions($qb, $productId, $status)
                ->getQuery()
                ->getSingleScalarResult()
            ;
        } catch (NonUniqueResultException $e) {
            return 0;
        }
    }

    /**
     * @return QueryBuilder
     */
    protected function getQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('product_review');
    }

    /**
     * @param QueryBuilder $qb
     * @param int $productId
     * @param string $status
     *
     * @return QueryBuilder
     */
    protected function injectProductIdAndStatusConditions(
        QueryBuilder $qb,
        int $productId,
        string $status = ProductReview::STATUS_PUBLISHED
    ): QueryBuilder {
        return $qb
            ->andWhere('product_review.status = :status')
            ->andWhere('product_review.product = :productId')
            ->setParameters([
                'status' => $status,
                'productId' => $productId
            ])
        ;
    }
}

<?php

namespace Smile\Bundle\ProductReviewBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Oro\Bundle\ProductBundle\Entity\Product;
use Smile\Bundle\ProductReviewBundle\Entity\ProductReview;
use Smile\Bundle\ProductReviewBundle\Entity\Repository\ProductReviewRepository;

/**
 * Class ProductReviewManager
 */
class ProductReviewManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * ProductDataProvider constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Product $product
     */
    public function updateRating(Product $product): void
    {
        $product->setRating($this->calculateRating($product));

        $this->entityManager->flush();
    }

    /**
     * @param Product $product
     *
     * @return float|null
     */
    public function calculateRating(Product $product): ?float
    {
        $productReviews = $this->getProductReviews($product);

        if (empty($productReviews)) {
            return null;
        }
        $sumRating = $this->getSumRating($productReviews);

        return $sumRating / count($productReviews);
    }

    /**
     * TODO:: If need, create more detail function
     *
     * @param float $rating
     *
     * @return float
     */
    public function getFormattedProductRating(float $rating): float
    {
        return ceil($rating * 2) / 2;
    }

    /**
     * @param Product $product
     *
     * @return array|null
     */
    protected function getProductReviews(Product $product): ?array
    {
        return $this->getProductReviewRepository()->getReviewsByProductIdAndStatus($product->getId());
    }

    /**
     * @param array $productReviews
     *
     * @return float
     */
    protected function getSumRating(array $productReviews): float
    {
        $sumRating = 0;

        /** @var ProductReview $productReview */
        foreach ($productReviews as $productReview) {
            $sumRating += $productReview->getRating();
        }

        return $sumRating;
    }

    /**
     * @return ProductReviewRepository
     */
    protected function getProductReviewRepository(): ProductReviewRepository
    {
        return $this->entityManager->getRepository(ProductReview::class);
    }
}

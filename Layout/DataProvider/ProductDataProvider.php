<?php

namespace Smile\Bundle\ProductReviewBundle\Layout\DataProvider;

use Doctrine\ORM\EntityManagerInterface;
use Oro\Bundle\ProductBundle\Entity\Product;
use Smile\Bundle\ProductReviewBundle\Entity\ProductReview;
use Smile\Bundle\ProductReviewBundle\Entity\Repository\ProductReviewRepository;
use Smile\Bundle\ProductReviewBundle\Manager\ProductReviewManager;

/**
 * Class ProductDataProvider
 */
class ProductDataProvider
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var ProductReviewManager
     */
    protected $productReviewManager;

    /**
     * ProductDataProvider constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ProductReviewManager $productReviewManager
     */
    public function __construct(EntityManagerInterface $entityManager, ProductReviewManager $productReviewManager)
    {
        $this->entityManager = $entityManager;
        $this->productReviewManager = $productReviewManager;
    }

    /**
     * Get product rating
     *
     * @param Product $product
     *
     * @return float|null
     */
    public function getRating(Product $product): ?float
    {
        return $product->getRating()
            ? $this->productReviewManager->getFormattedProductRating((floatval($product->getRating())))
            : null;
    }

    /**
     * @param Product $product
     *
     * @return int|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getCountReviews(Product $product): ?int
    {
        return $this->getProductReviewRepository()->getCountReviewsByProductIdAndStatus($product->getId());
    }

    /**
     * Get product id
     *
     * @param Product $product
     *
     * @return int
     */
    public function getProductId(Product $product): int
    {
        return $product->getId();
    }

    /**
     * @return ProductReviewRepository
     */
    protected function getProductReviewRepository(): ProductReviewRepository
    {
        return $this->entityManager->getRepository(ProductReview::class);
    }
}

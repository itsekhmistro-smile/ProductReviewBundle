<?php

namespace Smile\Bundle\ProductReviewBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Oro\Bundle\ProductBundle\Entity\Product;
use Oro\Bundle\WebsiteSearchBundle\Event\IndexEntityEvent;
use Oro\Bundle\WebsiteSearchBundle\Manager\WebsiteContextManager;
use Smile\Bundle\ProductReviewBundle\Entity\ProductReview;
use Smile\Bundle\ProductReviewBundle\Provider\ProductReviewFieldNameProvider;

/**
 * Class WebsiteSearchProductIndexerListener
 */
class WebsiteSearchProductIndexerListener
{
    /**
     * @var WebsiteContextManager
     */
    protected $websiteContextManager;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * WebsiteSearchProductIndexerListener constructor.
     *
     * @param WebsiteContextManager $websiteContextManager
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(WebsiteContextManager $websiteContextManager, EntityManagerInterface $entityManager)
    {
        $this->websiteContextManager = $websiteContextManager;
        $this->entityManager = $entityManager;
    }

    /**
     * @param IndexEntityEvent $event
     */
    public function onWebsiteSearchIndex(IndexEntityEvent $event): void
    {
        /** @var Product[] $products */
        $products = $event->getEntities();

        // iterate over entities that have to be indexed
        foreach ($products as $product) {
            $event->addField(
                $product->getId(),
                ProductReviewFieldNameProvider::RATING_FIELD_NAME,
                $product->getRating() ?? 0
            );
            $event->addField(
                $product->getId(),
                ProductReviewFieldNameProvider::COUNT_REVIEWS_FIELD_NAME,
                $this->entityManager->getRepository(ProductReview::class)
                    ->getCountReviewsByProductIdAndStatus($product->getId())
            );
        }
    }
}

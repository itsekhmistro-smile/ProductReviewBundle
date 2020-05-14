<?php

namespace Smile\Bundle\ProductReviewBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Oro\Bundle\ProductBundle\Entity\Product;
use Smile\Bundle\ProductReviewBundle\Entity\ProductReview;
use Smile\Bundle\ProductReviewBundle\Manager\ProductReviewManager;

/**
 * Class SetProductRatingSubscriber
 */
class SetProductRatingSubscriber implements EventSubscriber
{
    /**
     * @var ProductReviewManager
     */
    private $productReviewManager;

    /**
     * SetProductRatingSubscriber constructor.
     * @param ProductReviewManager $productReviewManager
     */
    public function __construct(ProductReviewManager $productReviewManager)
    {
        $this->productReviewManager = $productReviewManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::postUpdate
        ];
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args): void
    {
        $this->setProductRating($args);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    protected function setProductRating(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof ProductReview) {

            /** @var Product $product */
            $product = $entity->getProduct();
            $product->setRating($this->productReviewManager->calculateRating($product));
            $this->productReviewManager->updateRating($product);
        }
    }
}

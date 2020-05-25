<?php

namespace Smile\Bundle\ProductReviewBundle\Provider;

use Smile\Bundle\ProductReviewBundle\Entity\ProductReview;

/**
 * Class ProductReviewStatusProvider
 * @package Smile\Bundle\ProductReviewBundle\Provider
 */
class ProductReviewStatusProvider
{
    public const
        STATUS_PUBLISHED = 'Published',
        STATUS_NEEDS_REVIEW = 'Needs Review',
        STATUS_SPAM = 'Spam',
        STATUS_DELETED = 'Deleted',

        REVIEWS_STATUSES = [
            self::STATUS_PUBLISHED => ProductReview::STATUS_PUBLISHED,
            self::STATUS_NEEDS_REVIEW => ProductReview::STATUS_NEEDS_REVIEW,
            self::STATUS_SPAM => ProductReview::STATUS_SPAM,
            self::STATUS_DELETED => ProductReview::STATUS_DELETED
        ];

    /**
     * @return array
     */
    public function getAvailableProductReviewStatuses(): array
    {
        return self::REVIEWS_STATUSES;
    }
}

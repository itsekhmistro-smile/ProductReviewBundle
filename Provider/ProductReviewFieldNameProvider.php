<?php

namespace Smile\Bundle\ProductReviewBundle\Provider;

/**
 * Class ProductReviewFieldNameProvider
 */
class ProductReviewFieldNameProvider
{
    public const
        RATING_FIELD_NAME = 'rating',
        COUNT_REVIEWS_FIELD_NAME = 'countReviews',

        FIELD_NAMES = [
            self::RATING_FIELD_NAME,
            self::COUNT_REVIEWS_FIELD_NAME
        ];

    /**
     * @return array
     */
    public function getProductReviewFieldNames(): array
    {
        return self::FIELD_NAMES;
    }
}

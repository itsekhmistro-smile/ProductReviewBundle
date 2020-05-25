<?php

namespace Smile\Bundle\ProductReviewBundle\Provider;

/**
 * Class ProductReviewRatingChoiceProvider
 */
class ProductReviewRatingChoiceProvider
{
    public const
        RATING_ONE = 1,
        RATING_TWO = 2,
        RATING_THREE = 3,
        RATING_FOUR = 4,
        RATING_FIVE = 5,

        RATING_CHOICES = [
            self::RATING_ONE => self::RATING_ONE,
            self::RATING_TWO => self::RATING_TWO,
            self::RATING_THREE => self::RATING_THREE,
            self::RATING_FOUR => self::RATING_FOUR,
            self::RATING_FIVE => self::RATING_FIVE
        ]
    ;

    /**
     * @return array
     */
    public function getAvailableProductReviewRatingChoices(): array
    {
        return self::RATING_CHOICES;
    }
}

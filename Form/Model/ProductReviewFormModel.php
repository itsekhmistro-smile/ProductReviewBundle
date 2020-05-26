<?php

namespace Smile\Bundle\ProductReviewBundle\Form\Model;

use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue as RecaptchaTrue;
use Oro\Bundle\CustomerBundle\Entity\CustomerUser;
use Oro\Bundle\ProductBundle\Entity\Product;
use Smile\Bundle\ProductReviewBundle\Entity\ProductReview;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ProductReviewFormModel
 */
class ProductReviewFormModel
{
    public const
        POST_PRODUCT_REVIEW_WITH_RECAPTCHA_VALIDATION_GROUP = 'post_product_review_with_recaptcha',
        POST_PRODUCT_REVIEW_FOR_ANONYMOUS = 'post_product_review_for_anonymous',
        POST_PRODUCT_REVIEW_FOR_LOGGED = 'post_product_review_for_logged'
    ;

    /**
     * @var string
     *
     * @Assert\NotBlank(
     *     groups={
     *          ProductReviewFormModel::POST_PRODUCT_REVIEW_FOR_ANONYMOUS,
     *          ProductReviewFormModel::POST_PRODUCT_REVIEW_FOR_LOGGED
     *     },
     *     message="smile_product_review.validators.rating.not_blank"
     * )
     * @Assert\Range(
     *     min=ProductReview::MIN_RATING,
     *     max=ProductReview::MAX_RATING,
     *     minMessage="Min rating is {{ limit }}",
     *     maxMessage="Max rating is {{ limit }}",
     *     groups={
     *          ProductReviewFormModel::POST_PRODUCT_REVIEW_FOR_ANONYMOUS,
     *          ProductReviewFormModel::POST_PRODUCT_REVIEW_FOR_LOGGED
     *     },
     *     notInRangeMessage="smile_product_review.validators.rating.not_in_range"
     * )
     */
    public $rating;

    /**
     * @var string|null
     */
    public $comment;

    /**
     * @var string|null
     *
     * @Assert\NotBlank(
     *     groups={
     *          ProductReviewFormModel::POST_PRODUCT_REVIEW_FOR_ANONYMOUS
     *     },
     *     message="smile_product_review.validators.author.not_blank"
     * )
     */
    public $author;

    /**
     * @var Product
     *
     * @Assert\NotBlank(
     *     groups={
     *          ProductReviewFormModel::POST_PRODUCT_REVIEW_FOR_ANONYMOUS,
     *          ProductReviewFormModel::POST_PRODUCT_REVIEW_FOR_LOGGED
     *     },
     *     message="smile_product_review.validators.product.not_blank"
     * )
     */
    public $product;

    /**
     * @var CustomerUser|null
     */
    public $customerUser;

    /**
     * @var string|null
     *
     * @RecaptchaTrue(
     *     groups={
     *          ProductReviewFormModel::POST_PRODUCT_REVIEW_WITH_RECAPTCHA_VALIDATION_GROUP
     *     }
     * )
     */
    public $recaptcha;
}

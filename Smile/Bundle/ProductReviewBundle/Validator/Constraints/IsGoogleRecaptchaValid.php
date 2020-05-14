<?php

namespace Smile\Bundle\ProductReviewBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class IsGoogleRecaptchaValid
 *
 * @Annotation
 */
class IsGoogleRecaptchaValid extends Constraint
{
    public $message = 'smile_product_review.validators.is_google_recaptcha_valid.message';
}

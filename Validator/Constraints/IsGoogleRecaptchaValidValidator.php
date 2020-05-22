<?php

namespace Smile\Bundle\ProductReviewBundle\Validator\Constraints;

use Smile\Bundle\ProductReviewBundle\Manager\GoogleRecaptchaManager;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class IsGoogleRecaptchaValid
 */
class IsGoogleRecaptchaValidValidator extends ConstraintValidator
{
    /**
     * @var GoogleRecaptchaManager
     */
    protected $googleRecaptchaManager;

    /**
     * IsGoogleRecaptchaValidValidator constructor.
     *
     * @param GoogleRecaptchaManager $googleRecaptchaManager
     */
    public function __construct(GoogleRecaptchaManager $googleRecaptchaManager)
    {
        $this->googleRecaptchaManager = $googleRecaptchaManager;
    }

    /**
     * @param string $value
     * @param IsGoogleRecaptchaValid $constraint
     *
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof IsGoogleRecaptchaValid) {
            throw new UnexpectedTypeException($constraint, IsGoogleRecaptchaValid::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        $isValidGoogleRecaptcha = $this->googleRecaptchaManager->isValidGoogleRecaptcha($value);

        if (!$isValidGoogleRecaptcha) {
            $this->context->buildViolation($constraint->message)
                ->addViolation()
            ;
        }
    }
}

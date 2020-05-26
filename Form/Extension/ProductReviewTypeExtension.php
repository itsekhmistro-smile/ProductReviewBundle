<?php

namespace Smile\Bundle\ProductReviewBundle\Form\Extension;

use HackOro\RecaptchaBundle\Form\Extension\AbstractRecaptchaTypeExtension;
use Smile\Bundle\ProductReviewBundle\DependencyInjection\Configuration;
use Smile\Bundle\ProductReviewBundle\Form\Type\ProductReviewType;

/**
 * Class ProductReviewTypeExtension
 */
class ProductReviewTypeExtension extends AbstractRecaptchaTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function getExtendedTypes()
    {
        return [ProductReviewType::class];
    }

    /**
     * @return boolean
     */
    public function isProtected(): bool
    {
        return $this->configManager
            ->get(Configuration::getConfigKeyByName(Configuration::PROTECT_PRODUCT_REVIEW_FORM));
    }
}

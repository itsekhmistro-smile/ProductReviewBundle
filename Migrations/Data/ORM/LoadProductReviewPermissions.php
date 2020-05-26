<?php

namespace Smile\Bundle\ProductReviewBundle\Migrations\Data\ORM;

use Oro\Bundle\CustomerBundle\Migrations\Data\ORM\AbstractMassUpdateCustomerUserRolePermissions;
use Smile\Bundle\ProductReviewBundle\Entity\ProductReview;

/**
 * Class LoadProductReviewPermissions
 * @package Smile\Bundle\ProductReviewBundle\Migrations\Data\ORM
 */
class LoadProductReviewPermissions extends AbstractMassUpdateCustomerUserRolePermissions
{
    /**
     * {@inheritdoc}
     */
    protected function getACLData(): array
    {
        return [
            'ROLE_FRONTEND_ADMINISTRATOR' => [
                'entity:' . ProductReview::class => $this->getProductReviewPermissions(),
            ],
            'ROLE_FRONTEND_BUYER' => [
                'entity:' . ProductReview::class => $this->getProductReviewPermissions(),
            ],
            'ROLE_FRONTEND_ANONYMOUS' => [
                'entity:' . ProductReview::class => $this->getProductReviewPermissions()
            ]
        ];
    }

    /**
     * @return array
     */
    protected function getProductReviewPermissions(): array
    {
        return ['VIEW_SYSTEM', 'CREATE_SYSTEM', 'EDIT_NONE', 'DELETE_NONE'];
    }
}
